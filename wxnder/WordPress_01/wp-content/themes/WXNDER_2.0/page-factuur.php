<?php
  if(!isset($_GET["id"])){
    echo 'Bestelling niet gevonden';
    $display = 'none';
    exit();
  }
  else{
    $id = $_GET['id'];
    $display= 'inline-block';
    $mysqli = new mysqli("rdbms.strato.de","U4442376","Wandeling2020!","DB4442376");
    $orderInfo = "SELECT * FROM Bestellingen WHERE ID=$id";
    $deelBestellingen = "SELECT * FROM Deelbestellingen WHERE OrderID=$id";
    $gebruikerInfo = "SELECT * FROM Gebruikers WHERE ID=$id";
    $voorraad = "SELECT * FROM Voorraad";
    $resultOrderInfo = $mysqli->query($orderInfo);
    $resultDeelBestellingen = $mysqli->query($deelBestellingen);
    $resultgebruikerInfo = $mysqli->query($gebruikerInfo);
    $resultVoorraad = $mysqli->query($voorraad);
    $bestelRow = array();
    if($resultOrderInfo->num_rows > 0){
      while($row = $resultOrderInfo->fetch_assoc()){
        $bestelRow = $row;
      }
    }
    else{
      echo "Bestelling niet gevonden";
      $display = 'none';
    }
    $gebruikerRow = array();
    if($resultgebruikerInfo->num_rows > 0){
      while($row = $resultgebruikerInfo->fetch_assoc()){
        $gebruikerRow = $row;
      }
    }
    else{
      echo "Gebruikergegevens niet gevonden";
      $display = 'none';
    }
    $deelbestellingen = array();
    if($resultDeelBestellingen->num_rows > 0){
      while($row = $resultDeelBestellingen->fetch_assoc()){
        $deelbestellingen[] = $row;
      }
    }
    $producten = array();
    if($resultVoorraad->num_rows > 0){
      while($row = $resultVoorraad->fetch_assoc()){
        $producten[] = $row;
      }
    }


    $datum = $bestelRow['Aangemaakt'];
    $id = $bestelRow['ID'];

    $adres = $gebruikerRow['Adres'];
    $postcode = $gebruikerRow['Postcode'];
    $stad = $gebruikerRow['Stad'];
    $email = $gebruikerRow['Email'];
    $telefoonnummer = $gebruikerRow['Telefoonnummer'];
    $voornaam = $gebruikerRow['Voornaam'];
    $achternaam = $gebruikerRow['Achternaam'];


  }

 ?>
 <!doctype HTML>
<html>
<head><title>Factuur <?php echo $id?></title>
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Muli:ital,wght@0,300;0,400;0,500;0,700;0,900;1,400&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=PT+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
  <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->

  <style>

  body{
    box-sizing: border-box;
    background: #eeee;;
    margin:20px max(0px, calc(50vw - 400px));
    width: min(100%, 800px);
    max-width: 1000px;
    font-family: 'PT Sans', sans-serif;;
  }
  h1,h2,h3{
    font-family: 'Muli';
    font-weight: bold;
  }
  h1{
    font-size: 2em;
  }
  section{
    box-sizing: border-box;
    background: white;
    padding:60px;
    width: 100%;
    min-height: 1000px;
  }
  #main{
    display: <?php echo $display; ?>;
    position: relative;
  }
  #klant-gegevens{
  display: inline-block;
  float:left;
  }
  #wxnder-gegevens{
    display: inline-block;
    float: right;
    text-align: right;
    /* margin-right:50px; */
  }
  #producten{
    /* position: absolute;
    top:400px;
    left:0; */
    /* float:left; */
    /* display: block; */
    margin-top:150px;
  }
  #totaal-berekening{
    position: absolute;
    top:1000px;
    left:0;
  }
  p{
    margin:2px;
    font-size:12px;
  }

  #margin-row td{
    padding-top:50px;
  }
  td{
    font-size: 13px;
  }
  th{
    font-size: 14px;
  }
  </style>
</head>
<body>
  <section id='main'>
    <center>
  <img src='<?php echo get_template_directory_uri()?>/img/logo_big.svg' style='width:200px;filter:invert(1);'>
</center>
  <h1>Factuur</h1>
  <p id='datum'><?php echo $datum; ?></p>
  <div style="margin-top:20px;display:inline-block; width:100%;">
  <div id='klant-gegevens'>
    <p><?php echo "$voornaam $achternaam"; ?></p>
    <p><?php echo $email; ?></p>
    <p><?php echo $telefoonnummer; ?></p>
    <p><?php echo $adres; ?></p>
    <p><?php echo "$postcode $stad"; ?></p>
  </div>
  <div id='wxnder-gegevens'>
    <p>WXNDER</p>
    <p>Lomboklaan 7</p>
    <p>3956DE Leersum</p>
    <p>info@wxnder.nl</p>
    <p>NL861264368B01</p>
    <p>78105005</p>



  </div>
</div>
  <div id='producten'>
    <table width=100%; style="text-align:left;">
      <tbody>
        <tr><th>Product</th><th>Aantal</th><th>EXCL BTW</th><th>BTW</th><th style="text-align:right;">Bedrag</th></tr>
    <?php
      $subtotaal = 0;
      $btw_9 = 0;
      $btw_21 = 0;
      $totaal = 0;
    foreach($deelbestellingen as $bestelling){
      $id = $bestelling['OrderID'];
      $prID = $bestelling['ProductID'];
      $prijs = $bestelling['Prijs'];
      $btw = $bestelling['BTW'];
      $aantal = $bestelling['Aantal'];
      $bedrag = number_format($aantal * $prijs,2);
      $ex = round($prijs / (1 + ($btw/100)),2);
      $exbedrag = number_format($aantal * $ex,2);
      if($btw ==9){
        $btw_9+=$bedrag - $exbedrag;
      }
      else{
        $btw_21+=$bedrag - $exbedrag;
      }
      $subtotaal += $exbedrag;
      $totaal += $bedrag;

      $naam = 'Onbekend product';
      foreach($producten as $product){
        if($prID == $product['ID']){
          $naam = $product['Naam'];
        }
      }
      echo "<tr><td>$naam</td><td>$aantal</td><td>&euro;$exbedrag</td><td>$btw%</td><td style='text-align:right;'>&euro;$bedrag</td></tr>";
    }
     ?>
     <tr></tr>
     <tr></tr>
     <tr></tr>
   </tbody>
   <tfoot style="margin-top:20px;">
     <tr id='margin-row'><td></td><td></td><td></td><td></td></tr>
     <tr id='first-foot'><td></td><td></td><td>Subtotaal</td><td></td><td style='text-align:right;'>&euro;<?php echo $subtotaal;?></td></tr>
     <tr><td></td><td></td><td>9%</td><td></td><td style='text-align:right;'>&euro;<?php echo $btw_9;?></td></tr>
     <tr><td></td><td></td><td>21%</td><td></td><td style='text-align:right;'>&euro;<?php echo $btw_21;?></td></tr>
     <tr><td></td><td></td><th>Totaal</th><td></td><th style='text-align:right;'>&euro;<?php echo $totaal ;?></th></tr>
   </tfoot>
 </table>
  </div>
  <div id='totaal-berekening'></div>
  </section>
</body>

</html>
