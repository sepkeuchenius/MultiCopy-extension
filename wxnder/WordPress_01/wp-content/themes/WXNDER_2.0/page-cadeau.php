<?php
    // echo __DIR__;
      $mysqli = new mysqli("rdbms.strato.de","U4442376","Wandeling2020!","DB4442376");

      // Check connection
      if ($mysqli -> connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
        exit();
      }
      $queryVoorraad = "SELECT * FROM `Voorraad` ORDER BY `ID`";
      $resultVoorraad = $mysqli->query($queryVoorraad);

      $vooraad = array();
      if( $resultVoorraad->num_rows > 0){
        while ($row = $resultVoorraad->fetch_assoc()) {
          $voorraad[] = $row;
        }
      }


      require_once __DIR__ . "/Mollie/vendor/autoload.php";
      require_once __DIR__ . "/resources/slack.php";
      require_once __DIR__ . "/resources/log.php";

    // require_once __DIR__ . "/vendor/functions.php";

      $mollie = new \Mollie\Api\MollieApiClient();
			// echo $mollie;

      $mollie->setApiKey("live_5myAsvKxUNxz9JNFzjHGnRhvjrKpMT");

      $bestelling_id = $_POST["Ordernumber"];
      $cost = $_POST["Totaalprijs"];
      $newOrder = FALSE;
      if ( isset( $_POST['submit'] ) ) { // retrieve the form data by using the element's name attributes value as key $firstname = $_POST['firstname']; $lastname = $_POST['lastname']; // display the results
        $newOrder = TRUE;
        $data = [
            "amount" => [
              "value" => $cost,
              "currency" => "EUR"
              ],
              'metadata'=>[
                'name' => $_POST["Naam"],
                'dateofbirth' => $_POST['Geboortedatum'],
                'specificaties' => $_POST['Specificaties'],
                'leverdatum' => $_POST['Leverdatum'],
                ],
              "orderNumber" => strval($bestelling_id),
              "redirectUrl" => "https://wxnder.nl/betaald",
              "webhookUrl" => "https://wxnder.nl/webhook/?theme=WXNDER_2.0",
              "method" => "ideal",
              "locale" => "nl_NL",
              "lines" =>[[
                  "name" => "WXNDER Cadeaukaart " . " | " . $_POST["Samenstelling"] ,
                  "quantity" => "1",
                  "unitPrice" => [
                    "value" => $cost,
                    "currency" => "EUR"
                    ],
                  "totalAmount" =>[
                    "value" => $cost,
                    "currency" => "EUR"
                    ],
                    "vatRate" => "00.00",
                  "vatAmount" =>[
                    "value" => "0.00",
                    "currency" => "EUR"
                    ]

                  ]

                ],
              "billingAddress" => [
                  "streetAndNumber" => $_POST["Factuur_straatnaam"],
                  "postalCode" => $_POST["Factuur_postcode"],
                  "city" => $_POST["Factuur_stad"],
                  "country" => "NL",
                  "givenName" => $_POST["Naam"],
                  "familyName"=> $_POST["Achternaam"],
                  "email" =>$_POST["Email"]
                ],
              "shippingAddress" => [
                "streetAndNumber" => $_POST["Straatnaam"],
                  "postalCode" => $_POST["Postcode"],
                  "city" => $_POST["Stad"],
                  "country" => "NL",
                  "givenName" => $_POST["Naam"],
                  "familyName"=> $_POST["Achternaam"],
                  "email" => $_POST["Email"]
                ]

            ];
				// echo $mollie;
				try{
					$order = $mollie->orders->create($data);

				}
				catch(Exception $e){
					echo $e;
				}
        $straatnaam = "'".$_POST["Straatnaam"]."'";
        $stad ="'".$_POST["Stad"]."'";
        $postcode = "'".$_POST["Postcodes"]."'";
        $leverdatum = "'".$_POST["Leverdatum"]."'";
        $samenstelling =  "'".$_POST["Samenstelling"]."'";
        $specificaties =  "'".$_POST["Specificaties"]."'";
        $samenstellingID =  $_POST["SamenstellingID"];


        $voornaam ="'".$_POST["Naam"]."'";
        $achternaam ="'".$_POST["Achternaam"]."'";
        $geboortedatum ="'".$_POST["Geboortedatum"]."'";
        $telefoonnummer ="'".$_POST["Telefoonnummer"]."'";
        $email ="'".$_POST["Email"]."'";
        $adres ="'".$_POST["Factuur_straatnaam"]."'";
        $postcode ="'".$_POST["Factuur_postcode"]."'";
        $stad ="'".$_POST["Factuur_stad"]."'";
        $geslacht ="'".$_POST["Geslacht"]."'";

			//
        // $cost = "'".$cost."'";
        slack("Nieuwe Cadeaukaart bestelling \n".$_POST['Naam']." ".$_POST['Achternaam']."\n".str_replace("&&", "\n", $_POST["Samenstelling"])."\nID: ".$bestelling_id, '#verkoop');
        logMessage($bestelling_id, "Bestelling aangemaakt");
        $newOrderSQL = "INSERT INTO `Bestellingen` (ID, Prijs, Product, ProductSpecificatie, GebruikerID, LeverAdres, LeverPlaats, LeverPostcode, Aangemaakt, GekozenLevering)
        VALUES ($bestelling_id, $cost, 'CADEAU', $specificaties,$bestelling_id,$straatnaam, $stad, $postcode, NOW(), $leverdatum); ";
        // echo $samenstellingID;
        if (strlen($samenstellingID) > 0) {
          $IDSplit = split(' && ', $samenstellingID);
          foreach($IDSplit as $samenstellingDeel){
            $deelSplit = split(' ', $samenstellingDeel);
            $deelAmount = $deelSplit[0];
            $deelProductID = $deelSplit[1];
            $btw=0;
            $prijs=0;
            foreach($voorraad as $pr){
              if($pr['ID'] == $deelProductID){
                $btw = $pr['BTW'];
                $prijs = $pr['Uitprijs'];
              }
            }
            if(strlen($deelAmount) == 0 OR strlen($deelProductID) == 0){continue;}
            $newOrderSQL .= "INSERT INTO `Deelbestellingen` (OrderID, ProductID, Aantal, Prijs, BTW) VALUES ($bestelling_id, $deelProductID, $deelAmount, $prijs, $btw);";
          }
          //add BOX

        }
        $newOrderSQL.="INSERT INTO `Gebruikers` (ID, Voornaam, Achternaam, Email, Geboortedatum, Telefoonnummer, Adres, Postcode, Stad, Geslacht) VALUES ($bestelling_id, $voornaam, $achternaam, $email, $geboortedatum, $telefoonnummer, $adres, $postcode, $stad, $geslacht);";
        $resultNewOrder = $mysqli->multi_query($newOrderSQL);
        if(!($resultNewOrder === TRUE)) {
          echo "Er is iets misgegaan met jouw bestelling! Neem contact op met info@wxnder.nl en we lossen het zo snel mogelijk voor je op.";
          echo $mysqli->error;
        }

      // echo $order->getCheckoutUrl();

      //send script to make a row inside the sheet

      // $url = 'https://enob4bv4hohs9et.m.pipedream.net';
      // $curl = curl_init();
      // curl_setopt($curl, CURLOPT_URL, $url);
      // curl_setopt($curl, CURLOPT_POST, TRUE);
      // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      // curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
      // $header = ['Content-Type: application/json'];
      // curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
      // curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
      // $response = curl_exec($curl);
      //

      try{
      header("Location: " . $order->getCheckoutUrl(), true, 303);
      }
      catch(Exception $e){
        echo "Fout: " . htmlspecialchars($e->getMessage());

      }
    }


  $queryLevertijden = "SELECT * FROM `Levertijden` ORDER BY `Datum`, `Begintijd`";
  $resultLevertijden = $mysqli->query($queryLevertijden);
  $leverdata = array();
  if( $resultLevertijden->num_rows > 0){
    while ($leverRow = $resultLevertijden->fetch_assoc()) {
      // echo $leverRow['ID'];
      $uiterste = new DateTime($leverRow['Uiterste-bestelling']);
      $now = new DateTime();
      if($uiterste > $now and $leverRow['Max-leveringen'] > $leverRow['Huidige-leveringen']){
        $leverdata[] = $leverRow;
        // $leverdata[]['Begintijd'] = substr($leverRow['Begintijd'], 0, 5);
        // $leverdata[]['Eindtijd'] = substr($leverRow['Eindtijd'], 0 5);
      }

    }
  }



 ?>
<?php get_header() ?>


<img src='<?php echo get_template_directory_uri(); ?>/img/promo/cadeau_1.jpg?auto=enhance&fm=webp&q=10&fit=crop&crop=faces&ar=0.8&bri=-30' id='background-mobiel' style="top:;" class="crop">
<img src='<?php echo get_template_directory_uri(); ?>/img/promo/cadeau_1.jpg?auto=enhance&fm=webp&q=10&bri=-20' id='background' style="top:;">

<div class="page_content">
	<center id='page-landing'>
		<br>
	<h1 class="header_title" style="color:white">Geef een wonder</h1>
	<br>
	<p class="white" style=" font-size:20px;">Zoek jij nog een origineel cadeau voor moederdag of een andere gelegenheid? Met de <span class="wxnder_text">WXNDER</span>  cadeaukaart geef je een unieke ervaring.</p>
<br>
</center>
</div>

<div class='content_section_hor'>
<div class='hor_section'>
	<div class='section_title'>
<h2>Dè ervaring</h2>
</div>
<img src = '<?php echo get_template_directory_uri(); ?>/img/drawings/star_night.svg' class='hor_section_img'id='starnight'style="height:150px"><br>
<!-- <p style="text-align: left;max-width:500px;">Het team van <span style="" class= 'wxnder_text' >WXNDER</span> is continu bezig met het zoeken naar de mooiste plekjes in de natuur voor onze routes.</p> -->
<p class="hor_section_text">Bij WXNDER draait alles om de ervaring. We leveren hoogwaardige producten, en geven je keuze uit de allermooiste routes. De ontvanger kiest de route en beleeft een avontuur dat nog lang niet vergeten zal worden.</p>
<!-- <br><a href="/routes" class="link">Bekijk routes</a> -->

</div>

<div class="hor_section">
	<div class='section_title'>
<h2>Cadeauservice</h2>
</div>
<img src = '<?php echo get_template_directory_uri(); ?>/img/drawings/walking.svg'class='hor_section_img' style="height:150px"><br>
<p class="hor_section_text">Wij begrijpen dat mama het beste weet wat zij wil borrelen. Jij geeft een cadeaukaart voor een uniek product, zij stelt het samen. Jij bepaalt de prijs, zij bepaalt de inhoud. Wij leveren, en daar hoeft mama niets meer voor te betalen.</p>
<br>
<!-- <a href='/hoewerkthet' class="link">Hoe werkt het</a> -->

</div>
<div class='hor_section'>
	<div class='section_title'>
<h2>Duurzaam design</h2>
</div>
<img src='<?php echo get_template_directory_uri(); ?>/img/drawings/lokale ondernemers.svg'class='hor_section_img' style="height:150px"><br>
<p class="hor_section_text"> Onze producten zijn niet alleen goed voor de natuur, maar zijn ook nog eens van hoogwaardig design. Jouw cadeaukaart wordt gedrukt op een stuk hout, en vangt meteen de aandacht op de cadeautafel.</p>
<!-- <br><a href='/partners'class="link">Onze partners</a> -->

</div>
</div>
<div id='pictures'>
	<div id='pic_right'  style="background: url('<?php echo get_template_directory_uri(); ?>/img/promo/cadeau-binnen-voor.jpg?auto=enhance&fm=webp&q=10') center/cover white">
		<!-- <img src='<?php echo get_template_directory_uri(); ?>/img/go_slides_3.jpg'> -->
	</div>
	<div id='pics_left'>
		<div class='pic_left' style="background: url('<?php echo get_template_directory_uri(); ?>/img/promo/cadeau-binnen-voor.jpg?auto=enhance&fm=webp&q=10') center/cover white">
			<!-- <img src='' style="padding-top:10px"> -->
		</div>
		<div class='pic_left' style="background: url('<?php echo get_template_directory_uri(); ?>/img/promo/cadeau-buiten-achter.jpg?auto=enhance&fm=webp&q=10') center/cover white">
				<!-- <img src='<?php echo get_template_directory_uri(); ?>/img/routes/heide.jpeg'  style="padding-top:10px"> -->
		</div>
		<div class='pic_left' style="background: url('<?php echo get_template_directory_uri(); ?>/img/promo/heide2.jpg?auto=enhance&fm=webp&q=10') center/cover white">
			<!-- <img src='<?php echo get_template_directory_uri(); ?>/img/routes/heide2.jpg'style="padding-top:10px"> -->
		</div>
		<div class='pic_left'  style="background: url('<?php echo get_template_directory_uri(); ?>/img/promo/sapjes_nootjes.jpg?auto=enhance&fm=webp&q=10') center/cover white">
			<!-- <img src='<?php echo get_template_directory_uri(); ?>/img/box_slides_5.jpg' style="padding-top:10px"> -->
		</div>
		<div class='pic_left' style="background: url('<?php echo get_template_directory_uri(); ?>/img/promo/cadeau-binnen.jpg?auto=enhance&fm=webp&q=10') center/cover white">
			<!-- <img src='<?php echo get_template_directory_uri(); ?>/img/go_slides_3.jpg' style="padding-top:10px"> -->
		</div>
	</div>

</div>
<div class="section-green">
<center><h1 class="white-header">In het kort</h1>
  <p class="left-item">Jij bestelt de cadeaukaart en kiest voor BOX of GO. De BOX is een borrelbox, de GO een borreltas.</p>
  <img src='<?php echo get_template_directory_uri(); ?>/img/drawings/arrow.svg' class="arrow_down">
  <p class="right-item">Wij maken jouw cadeaukaart gepersonaliseerd op een dun soort hout.</p>
  <img src='<?php echo get_template_directory_uri(); ?>/img/drawings/arrow.svg' class="arrow_down">
  <p class="left-item">Wij zorgen dat de cadeaukaart op tijd geleverd wordt, bij jou of bij de ontvanger.</p>
  <img src='<?php echo get_template_directory_uri(); ?>/img/drawings/arrow.svg' class="arrow_down">
  <p class="right-item">Op de cadeaukaart vindt de ontvanger een code die hij of zij online kan verzilveren.</p>
  <img src='<?php echo get_template_directory_uri(); ?>/img/drawings/arrow.svg' class="arrow_down">
  <p class="left-item">De ontvanger stelt het product samen naar zijn of haar wensen en kiest een mooie route.</p>
  <img src='<?php echo get_template_directory_uri(); ?>/img/drawings/arrow.svg' class="arrow_down">
  <p class="right-item">De ontvanger krijgt het product gratis thuisbezorgd en kan op avontuur!</p>
</center>
</div>

<center>
<div id='bestelproces'>
	<div class="bestelpagina" id="bestelpagina_0">
		<h1 class="bestelpagina_titel">Kies je cadeaukaart</h1>
    <h3 style="color:red">Op dit moment hebben wij helaas geen cadeaukaarten op voorraad</h3>

    <p>Gratis verzending</p>
    <!-- <p>De ontvanger van de kaart kan zelf de box/go samenstellen</p>
    <ul style="max-width:300px;text-align:left;">

    <li>Gratis verzending</li>
    <li>Mooie cadeaukaart van hout</li>
		<li>Eigen berichtje</li>

    </ul> -->

  	<div class='cadeau_option' id='option_box'>
			<img src='https://wxnder.imgix.net/products/box.png?auto=enhance&fm=webp&q=10' class="cover_image">
		  <center> <h2 class="box_spec_title">CADEAUKAART</h2> </center>
      <div class="option-logo-outer">
        <img src='<?php echo get_template_directory_uri(); ?>/img/products/box_logo.svg'class="option-logo">
      </div>
			<h2 class="box_spec_price">&euro;39,99</h2>
		</div>

		<div class='cadeau_option' id='option_go'>
			<img src='https://wxnder.imgix.net/products/go.png?auto=enhance&fm=webp&q=10' class="cover_image">
      <h2 class="box_spec_title">CADEAUKAART</h2>
      <div class="option-logo-outer">
        <img src='<?php echo get_template_directory_uri(); ?>/img/products/go_logo.svg' class="option-logo">
      </div>
			<h2 class="box_spec_price">&euro;24,99</h2>
		</div>
	</div>
	<div class="bestelpagina" id='bestelpagina_1'>
		<h1 class="bestelpagina_titel">Personaliseer je cadeaukaart</h1>

		<input type='text' placeholder="Ontvanger(s)" id='cadeau-ontvanger'><br>
		<textarea id='cadeau-bericht' placeholder="Berichtje voor..." cols="30" rows="7"></textarea><br>
		<br>
		<br>
		<br>

		<input type='text' placeholder="Namens" id='cadeau-namens'><br>
	</div>

	<div class="bestelpagina" id="bestelpagina_2">
		<h1 class="bestelpagina_titel">Gegevens</h1>
    <p>Vul <strong>jouw</strong> gegevens in</p>
		<div id='options_geslacht'class= 'options' style="padding:3px;width:fit-content;">
			<!--<p style="margin:0; color:rgb(150,150,150,1)">Aanhef*</p>-->
			<!--<div>-->
		<div class='option small geslacht' id='option_man'>Dhr</div>
		<div class='option small geslacht' id='option_vrouw'>Mevr</div>
		<div class='option small geslacht' id='option_x'>X</div>
		<!--<span style="font-size:2em"><sup>*</sup></span>-->
		<!--</div>-->
		<br>
		</div>
		<input type='text' class='' placeholder="Voornaam*" name='voornaam'id='bestelling_naam' >
		<input type='text' class='' placeholder="Achternaam*" id='bestelling_achternaam' name='achternaam' ><br>
		<input type='text' placeholder="Telefoonnummer*" id='bestelling_tel'name='tel' >
		<input type='text' placeholder="Email*" id='bestelling_email'name='email' ><br>

		<input type='text' placeholder="Straatnaam + Nr" id='bestelling_straatnaam' >
		<!--<input type='text' placeholder="HuisNr">-->
		<input type='text' id='bestelling_stad' placeholder="Stad" ><br>
		<input type='text' id='bestelling_postcode' placeholder="Postcode" >
		<input type='date' id='bestelling_geboortedatum' placeholder="Geboortedatum" value='1970-01-01'>
		<br>
		<br>
		<input type='checkbox' id='factuuradres_check'><span style="font-size:13px;color:#514848;"> Ander Factuuradres</span><br><br><br>
		<div id='factuuradres' style='display:none;'>
			<p class="smalltext" style="color:#514848">Factuuradres</p><br>
			<input type='text' placeholder="Straatnaam + Nr" id='bestelling_factuur_straatnaam'>
		<!--<input type='text' placeholder="HuisNr">-->
			<input type='text' id='bestelling_factuur_stad' placeholder="Stad / Plaats">
			<input type='text' id='bestelling_factuur_postcode' placeholder="Postcode (1234AB)">
			<br><br>
		</div>
	</div>
	<div class="bestelpagina" id="bestelpagina_3">
		<h1 class="bestelpagina_titel">Levering</h1>
		<br>
		<p>De levering wordt gedaan door POSTNL. De cadeaukaart past door de brievenbus.</p>
		<br>
		<br>
		<textarea id='extra_opmerkingen' placeholder="Extra opmerkingen" rows=4 cols=30></textarea>
	</div>

	<div class="bestelpagina" id="bestelpagina_4">
		<h1 class="bestelpagina_titel">Betaling</h1>
		<table style="color: #514848 !important;
	font-size: 16px;">
			<tbody>
				<tr id='firstrowgo'><td width=200>1 <span style="" class= 'wxnder_text' id='box-prijs'>WXNDER GO</span></td><td id='box-prijs-euro'>5.49</td></tr>

				<tr><td>Verzendkosten</td><td id='verzendkosten'>&euro;3,99</td></tr>
				<tr><td></td><td></td></tr>
				<tr><td ><b>Totaal</b></td><td style="
					color: rgb(83,145,126);;
					background: #ece0e0eb;
					padding: 2px 6px;
					border-radius: 4px;"><b id='total_amount'>&euro;13,98</b></td></tr>

			</tbody>
		</table><br><br><br>
		<form style="display:unset; padding:0;margin:0;" method="post" action='' id='resultsx'>
			<!-- <a id='prevButton'  style="margin-right: 20px;"onclick= 'prevPage()'>Vorige</a> -->

			<input type='submit' name='submit' value='Betalen' id='submit' style='
			background: #5f9b8b;
	/* border: 1px solid orange; */
	font-weight: bold;
	font-size: 16px;
	font-family: inherit;
	cursor: pointer;
	text-transform: uppercase;
	padding: 6px 20px;'>
		</form>
	</div>

	        <div id='bestelbuttons'>
	          <a id='backbutton' onclick="bestelBack()"></a>
	          <a id='nextbutton' class="active" onclick='bestelNext()' style="display:none"></a>
	        </div>
</div>
</center>
<script src='<?php echo get_template_directory_uri(); ?>/js/bestelproces-cadeau.js'></script>
<?php get_footer() ?>
