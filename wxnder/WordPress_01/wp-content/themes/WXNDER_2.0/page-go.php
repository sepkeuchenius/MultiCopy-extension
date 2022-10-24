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
                  "name" => "WXNDER GO " . " | " . $_POST["Samenstelling"] ,
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

        $order = $mollie->orders->create($data);
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


        // $cost = "'".$cost."'";
        slack("Nieuwe GO bestelling \n".$_POST['Naam']." ".$_POST['Achternaam']."\n".str_replace("&&", "\n", $_POST["Samenstelling"])."\nID: ".$bestelling_id, '#verkoop');
        logMessage($bestelling_id, "Bestelling aangemaakt");
        $newOrderSQL = "INSERT INTO `Bestellingen` (ID, Prijs, Product, ProductSpecificatie, GebruikerID, LeverAdres, LeverPlaats, LeverPostcode, Aangemaakt, GekozenLevering)
        VALUES ($bestelling_id, $cost, 'GO', $specificaties,$bestelling_id,$straatnaam, $stad, $postcode, NOW(), $leverdatum); ";
        // echo $samenstellingID;
        $samenstellingID .= "1 473809405";
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


  $queryProductactiviteit = "SELECT * FROM `Productactiviteit` ORDER BY `ID`";
  $resultProductactiviteit = $mysqli->query($queryProductactiviteit);

  $producten = array();
  if( $resultProductactiviteit->num_rows > 0){
    while ($row = $resultProductactiviteit->fetch_assoc()) {
      foreach($voorraad as $v){
        if($v['ID'] == $row['ID']){
            //found
            if($v['Huidige-voorraad'] > $row['Ondergrens'] and $row['GO-Actief'] === '1'){
              $product = [
                'ID' => $v["ID"],
                'Huidige-Voorraad' => $v["Huidige-voorraad"],
                'Prijs' => $v['Uitprijs'],
                'Naam' => $v["Naam"],
                'Details' => $v['Details'],
                'Beschrijving' => $row['GO-Beschrijving'],
                'Leverancier' => $v['Leverancier'],
              ];
              array_push($producten, $product);
              break;
            }
        }
      }
    }
  }

 ?>
<?php get_header() ?>

<script src='https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.js' ></script>
<link href='https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.css' rel='stylesheet' />

<!-- <img src='<?php echo get_template_directory_uri(); ?>/img/go_background.jpg' id='background' style="opacity:0.7"> -->
<img src='<?php echo get_template_directory_uri(); ?>/img/go_background.jpg?auto=enhance&fm=webp&q=10&fit=crop&crop=faces&ar=0.7' id='background-mobiel' style="top:;">
<img src='<?php echo get_template_directory_uri(); ?>/img/go_background.jpg?auto=enhance&fm=webp&q=10' id='background' style="top:;">

<div class='page_content'>
  <center>
  <img src='<?php echo get_template_directory_uri(); ?>/img/products/go_logo.svg' class='product_title'>
  <h1 class='white-header' style="" id=''>Cre&euml;er jouw avontuur</h1>
  <p class="white">Vanaf &euro;8,99</p>
</center>
  <div id='box_header'>
    <div class='product_left' style="box-shadow:none;">
      <img src='<?php echo get_template_directory_uri(); ?>/img/products/go.png?auto=enhance&fm=webp&q=10' class='product_image'>

  	</div>
    <a class="active" onclick="bestelBox()" id='bestelknop--mobiel'>Bestel</a>

    <div id="go_text" >
      <p style="background: url('<?php echo get_template_directory_uri(); ?>/img/drawings/scratch6.svg') center/cover no-repeat;background-color:none;">WXNDER GO is het kleinere broertje van de BOX, gericht op efficiëntie en gemak. In plaats van een box ontvang je een prachtige, stevige tas die je voor de rest van je leven kan gebruiken.</p><br>
      <div id='pictos'>
        <img src="<?php echo get_template_directory_uri(); ?>/img/pictos/juice.svg"><span>Sapjes</span><br>
        <img src="<?php echo get_template_directory_uri(); ?>/img/pictos/nootjes.svg"><span>Nootjes</span><br>
        <img src="<?php echo get_template_directory_uri(); ?>/img/pictos/route_logo_wit.svg"><span>Route</span><br>
        <img src="<?php echo get_template_directory_uri(); ?>/img/cart_white.svg"><span>Aanvullingen</span>
        <br>
        <br>
        <a class="active" onclick="bestelBox()" id='bestelknop'>Bestel</a><br>
      </div>

    </div>

  </div>

  <div class='content_section_hor'>
    <div class='hor_section'>
      <h2>Borrel</h2>
      <img src="<?php echo get_template_directory_uri(); ?>/img/drawings/borrel.svg" class='hor_section_img'>
      <p class='hor_section_text'>Met z'n tweeen met heerlijke nootjes van de Nootjesfabriek en sapjes van Vernooij uit Cothen. Daarnaast kun je bij het samenstellen van GO andere lokale lekkernijen toevoegen, zoals biertjes, wijntjes, worst en nog veel meer.</p>
    </div>
    <div class='hor_section even'>
      <h2>Verken</h2>
      <img src="<?php echo get_template_directory_uri(); ?>/img/drawings/verken.svg" class='hor_section_img'>
      <p class='hor_section_text'>We hebben in Utrecht al een aantal prachtige routes uitgezet die je langs de mooiste plekken leiden en bereikbaar zijn met het OV. Met een prachtige handgemaakte houten routekaart krijg je aanwijzingen om de mooiste route ooit te lopen.</p>
    </div>
    <div class='hor_section'>
      <h2>Geniet</h2>
      <img src="<?php echo get_template_directory_uri(); ?>/img/drawings/geniet.svg" class='hor_section_img'>
      <p class='hor_section_text'>Hapjes en drankjes van hoogstaande kwaliteit, vers gemaakt door de boeren en bakkers bij jou in de buurt. Het is een ideale originele date, maar ook een manier om een middag door te brengen met een geliefde.</p>
    </div>

  </div>
  <div id='pictures'>
    <div id='pic_right'  style="background: url('<?php echo get_template_directory_uri(); ?>/img/promo/go_combo.jpg?auto=enhance&fm=webp&q=10') center/cover white">
      <!-- <img src='<?php echo get_template_directory_uri(); ?>/img/go_slides_3.jpg'> -->
    </div>
    <div id='pics_left'>
      <div class='pic_left' style="background: url('<?php echo get_template_directory_uri(); ?>/img/promo/go_combo.jpg?auto=enhance&fm=webp&q=10') center/cover white">
        <!-- <img src='' style="padding-top:10px"> -->
      </div>
      <div class='pic_left' style="background: url('<?php echo get_template_directory_uri(); ?>/img/promo/wijn_bier_worst.jpg?auto=enhance&fm=webp&q=10') center/cover white">
          <!-- <img src='<?php echo get_template_directory_uri(); ?>/img/routes/heide.jpeg'  style="padding-top:10px"> -->
      </div>
      <div class='pic_left' style="background: url('<?php echo get_template_directory_uri(); ?>/img/promo/heide2.jpg?auto=enhance&fm=webp&q=10') center/cover white">
        <!-- <img src='<?php echo get_template_directory_uri(); ?>/img/routes/heide2.jpg'style="padding-top:10px"> -->
      </div>
      <div class='pic_left'  style="background: url('<?php echo get_template_directory_uri(); ?>/img/promo/sapjes_nootjes.jpg?auto=enhance&fm=webp&q=10') center/cover white">
        <!-- <img src='<?php echo get_template_directory_uri(); ?>/img/box_slides_5.jpg' style="padding-top:10px"> -->
      </div>
      <div class='pic_left' style="background: url('<?php echo get_template_directory_uri(); ?>/img/promo/denhaag.jpg?auto=enhance&fm=webp&q=10') center/cover white">
        <!-- <img src='<?php echo get_template_directory_uri(); ?>/img/go_slides_3.jpg' style="padding-top:10px"> -->
      </div>
    </div>

  </div>
<center>
  <div id='bestelproces' style="max-width:1300px;">
        <div class='bestelpagina' id='bestelpagina_0'>
      <div id='map_container'>
        <div id='map' style='width: 100%; height: 100%;'></div>
        <script src='<?php echo get_template_directory_uri(); ?>/js/routes.js'></script>
    <script>
      mapboxgl.accessToken = 'pk.eyJ1IjoibWVsbGVrZXVjaCIsImEiOiJjazd5dnNuNHIwMDE0M21tdzEzY3c3NnEwIn0.AkKYZr3Gnzb9NfoY37vIcQ';
      var map = new mapboxgl.Map({
      container: 'map',
      style: 'mapbox://styles/mellekeuch/cklqru5q764fs17qlb3oiblo1',
      center: [ 4.82, 52.035],
      zoom: 7.9,
      // causes pan & zoom handlers not to be applied, similar to
      // .dragging.disable() and other handler .disable() funtions in Leaflet.
      interactive: true,
    });
      for(var r in go_routes){
        var route = go_routes[r]
        console.log(route)
        console.log(route_informatie)
        var deze_route = route_informatie[route];
        console.log(deze_route);
        var el = document.createElement('div');
        el.className = 'marker';
        el.style.background = 'url(<?php echo get_template_directory_uri(); ?>/img/icons/marker.svg) center/80% no-repeat'
        el.style.width = '30px'
        el.style.height = '30px'
        el.style.zIndex = '2'
        el.id = 'marker-'+route;
        el.style.cursor = 'pointer';
        el.addEventListener('click', function(){
          selected['options_route'] = $(this).attr('id');
          var routeinfo = route_informatie[$(this).attr('id')]
          $('.marker').css('background-image','url(<?php echo get_template_directory_uri(); ?>/img/icons/marker.svg)' )
          var id = $(this).attr('id');
          var route = id.split('-')[1];
          $('.route').css('background', 'none')
          $('#' + route).css('background', 'orange');
          selected['route'] = $('#' + route).attr('id');
          this.style.backgroundImage = 'url(<?php echo get_template_directory_uri(); ?>/img/icons/marker_chosen.svg)'
        })
        new mapboxgl.Marker(el, {rotation:0})
        .setLngLat(route_informatie[route].begin)
        // .setColor('RED')
        .addTo(map);

      }
      map.scrollZoom.disable()
      map.doubleClickZoom.disable()
      </script>
      </div>
      <div id='route'>
        <h1 class='bestelpagina_titel'>Kies je route</h1>
        <h3 style="color:red">Op dit moment hebben wij helaas geen GO op voorraad</h3>
        <div id='labels'>
          <div class="label">Heuvelachtig</div>
        </div>





      </div>
    </div>
    <div class='bestelpagina' id='bestelpagina_1'>
      <h1 class="bestelpagina_titel">Samenstelling</h1>
      <div id='winkelmandje' onclick= '$("#productenlijst").slideToggle(); fillProducts()'>
        <img src='<?php echo get_template_directory_uri(); ?>/img/pictos/cart.svg' style="    width: 20%;
        height: 80%;
        margin-top: 4%;
        margin-left: 9%;
        opacity: 0.5;
    "> <span id='currentamount'>&euro;5.49</span>
      </div>
        <div id='productenlijst'></div>
      <div class='extra_products'>

        <?php
        foreach($producten as $product){
          echo "<div class='extra_product' id='".$product['ID']."'><h2 class='title'>".$product['Naam']."</h2><img class='extra_image' src='".get_template_directory_uri()."/img/pictos/nootjes.svg'>";
          echo "<div class='price'>&euro;".$product['Prijs']."</div>";
          echo "<div class='description'>";
          if(strlen($product['Details'])>0){
            echo $product['Details']."<br>";
          }

          if(strlen($product['Leverancier'])>0){
            echo $product['Leverancier']."<br>";
          }
          echo "</div>";
          echo "<div class='counter'><a class='counter_button neg'>-</a><span class='number'>0</span><a class='counter_button'>+</a></div></div>";

        }
         ?>

    </div>
    </div>

    <div class='bestelpagina' id='bestelpagina_2'>
      <h1 class="bestelpagina_titel">Gegevens</h1>
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
      <input type='checkbox' id='factuuradres_check'><span style="font-size:13px;color:#514848;"> Ander Factuuradres</span><br><br><br>
      <div id='factuuradres' style='display:none;'>
        <p class="smalltext" style="color:#514848">Factuuradres</p><br>
        <input type='text' placeholder="Straatnaam + Nr" id='bestelling_factuur_straatnaam'>
      <!--<input type='text' placeholder="HuisNr">-->
      <input type='text' id='bestelling_factuur_stad' placeholder="Stad / Plaats">
      <input type='text' id='bestelling_factuur_postcode' placeholder="Postcode (1234AB)">
      <br><br>
    </div>
    <!-- <p class="smalltext" style="    display: inline-block;
  width: 200px;
  text-align: center;;">Geboortedatum*</p> -->

  <input type='text' class='' placeholder="Naam Meeloper" name='voornaam'id='bestelling_naam_2'>

  </div>
    <div class='bestelpagina' id='bestelpagina_3'>
      <h1 class="bestelpagina_titel">Levering</h1>
  <!--
      <p style="max-width:700px">
        We leveren jouw <span style="" class= 'wxnder_text' >WXNDER</span> zo snel mogelijk, uiterlijk binnen een week. Zodra we weten wanneer we langskomen ontvang je een mailtje!
      </p> -->
      <div id='persoonlijkelevering'>
        <p class='smalltext'>Leveringen in en rond Utrecht worden persoonlijk door ons bezorgd!</p>
      <select id='levering' class='opties'>
        <option>Zo snel mogelijk</option>
        <?php
          foreach($leverdata as $row){
            $id = $row['ID'];
            $string = strtotime($row['Datum']);
            $datum = date('m-d-Y' , $string);
            $begintijd = substr($row['Begintijd'],0,5);
            $eindtijd = substr($row['Eindtijd'],0,5);


            echo "<option value='".$id."'>".$datum."  ".$begintijd." tot ".$eindtijd."</option>";
          }
        ?>
      </select><br><br>
    </div>
    <div id='postnl'>
      <p class='smalltext'>Leveringen buiten Utrecht worden verstuurd met POSTNL</p>
      <p class='smalltext'>Deze worden binnen 3 tot 5 dagen geleverd.</p>
  <!--
      <h2 class='option_title' style='border:none'>Waar mogen we de box bezorgen?</h2><br> -->
      <br><br>

    </div>

    <textarea id='extra_opmerkingen' placeholder="Extra opmerkingen" rows=2 cols=30></textarea>

    </div>
    <div class='bestelpagina' id='bestelpagina_4'>
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
      background: #4f917f;
  border: 1px solid #4f917f;
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
</div>
<script src='<?php echo get_template_directory_uri(); ?>/js/bestelproces-go.js'></script>
<?php get_footer() ?>
