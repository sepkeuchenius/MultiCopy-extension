<?php
$newQuery = false;
require_once __DIR__ . "/resources/log.php";
require_once __DIR__ . "/resources/mail.php";
logMessage('123', "Test");


// bestelBevestiging("septimber@gmail.com", "Sep", '1613668787246');

if(isset($_POST['lever-submit-postnl'])){
  $value = '"'.$_POST['trackandtrace'].'"';
  $orderN = $_POST['hiddenID'];
  $newQuery = "UPDATE `Bestellingen` SET LeverdatumID = ". $value ." WHERE ID = ".$orderN;

  if($_POST['email']){
    $leverEmail = true;
    $leverEmailID = $orderN;
    $leverEmailValue = $value;
    $POSTNL = true;
    $trackAndTrace = $value;
  }
}
if(isset($_POST['lever-submit'])){
  $value = $_POST['input-leverdatum'];
  $split = split('_' , $value);
  $orderN = $split[1];
  $value= $split[0];
  if($value == 'verwijderen'){$value = "0";}
  // else{
  //   // $value = split(' tot ', $value)[0];
  //   $value = split(')', $value)[0];
  //   $value = split('\(', $value)[1];
  //   $value = "'".$value."'";
  // }
  $newQuery = "UPDATE `Bestellingen` SET LeverdatumID = ". $value ." WHERE ID = ".$orderN;

  if($_POST['email']){
    $leverEmail = true;
    $leverEmailID = $orderN;
    $leverEmailValue = $value;
  }

}
if(isset($_POST['productie-submit'])){
  $value = $_POST['input-productiedatum'];
  $split = split('_' , $value);
  $orderN = $split[1];
  $value= $split[0];
  if($value == 'verwijderen'){$value = "0";}
  else{
    // $value = split(' tot ', $value)[0];
    $value = split(')', $value)[0];
    $value = split('\(', $value)[1];
    $value = "'".$value."'";
  }
  $newQuery = "UPDATE `Bestellingen` SET Productiedatum = ". $value ." WHERE ID = ".$orderN;
}
if(isset($_POST['geleverd-submit'])){
  $value = $_POST['input-geleverddatum'];
  $split = split('_' , $value);
  $orderN = $split[1];
  $value= $split[0];
  if($value == 'verwijderen'){$value = "'0000-00-00 00:00:00'";}
  if($value == 'nu'){$value = 'NOW()';}

  $newQuery = "UPDATE `Bestellingen` SET Geleverd = ". $value ." WHERE ID = ".$orderN;
}
if(isset($_POST['geproduceerd-submit'])){
  $value = $_POST['input-geproduceerddatum'];
  $split = split('_' , $value);
  $orderN = $split[1];
  $value= $split[0];
  if($value == 'nu'){$value = 'NOW()';}
  if($value == 'verwijderen'){$value = "'0000-00-00 00:00:00'";}


  $newQuery = "UPDATE `Bestellingen` SET Geproduceerd = ". $value ." WHERE ID = ".$orderN;
}

try{
$mysqli = new mysqli("rdbms.strato.de","U4442376","Wandeling2020!","DB4442376");

// Check connection
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}

if($newQuery){
  $resultNewQuery =  $mysqli->query($newQuery);
}

if($leverEmail){
  $emailQuery = "SELECT * FROM `Gebruikers` WHERE `ID`= $leverEmailID";
  $resultEmail = $mysqli->query($emailQuery);
  if( $resultEmail->num_rows > 0){
    while ($emailRow = $resultEmail->fetch_assoc()) {
      $email = $emailRow['Email'];
      $naam = $emailRow['Voornaam'];
      $postcode = $emailRow['LeverPostcode'];
    }
  }
  if($POSTNL){
    leverBevestigingPOSTNL($email, $naam , $leverEmailID,$trackAndTrace,$postcode);
  }
  else{
    $leverQuery = "SELECT * FROM `Levertijden` WHERE `ID`= $leverEmailValue";
    $resultLever = $mysqli->query($leverQuery);
    if( $resultLever->num_rows > 0){
      while ($leverRow = $resultLever->fetch_assoc()) {
        $begintijd = $leverRow['Begintijd'];
        $eindtijd = $leverRow['Eindtijd'];
        $datum = $leverRow['Datum'];
      }
    }
    $leverDatumText = "$datum tussen $begintijd en $eindtijd";

    leverBevestiging($email, $naam , $leverEmailID, $leverDatumText);
  }
}

$queryBestellingen = "SELECT * FROM `Bestellingen`";
$queryGebruikers = "SELECT * FROM `Gebruikers`";
$queryBoxSpecificaties = "SELECT * FROM `BoxSpecificaties`";
$queryGoSpecificaties = "SELECT * FROM `GoSpecificaties`";
$queryValentijnSpecificaties = "SELECT * FROM `ValentijnSpecificaties`";
$queryLevertijden = "SELECT * FROM `Levertijden`  where concat(datum, ' ', Begintijd) > now();";
$queryProductietijden = "SELECT * FROM `Productietijden`";
$queryVoorraad = "SELECT * FROM `Voorraad`";
$queryVoorraadgroepen = "SELECT * FROM `Voorraadgroepen`";

$resultBestellingen = $mysqli->query($queryBestellingen);
$resultGebruikers = $mysqli->query($queryGebruikers);
$resultBoxSpecificaties = $mysqli->query($queryBoxSpecificaties);
$resultGoSpecificaties = $mysqli->query($queryGoSpecificaties);
$resultValentijnSpecificaties = $mysqli->query($queryValentijnSpecificaties);
$resultLevertijden = $mysqli->query($queryLevertijden);
$resultProductietijden = $mysqli->query($queryProductietijden);
$resultVoorraad = $mysqli->query($queryVoorraad);
$resultVoorraadgroepen = $mysqli->query($queryVoorraadgroepen);

$leverdata = array();
if( $resultLevertijden->num_rows > 0){
  while ($leverRow = $resultLevertijden->fetch_assoc()) {
    // echo $leverRow['ID'];
    $leverdata[] = $leverRow;
  }
}
$productietijden = array();
if( $resultProductietijden->num_rows > 0){
  while ($productieRow = $resultProductietijden->fetch_assoc()) {
    // echo $leverRow['ID'];
    $productietijden[] = $productieRow;
  }
}

$gebruikers = array();
if($resultGebruikers->num_rows > 0){
  while($gebruikerRow = $resultGebruikers->fetch_assoc()){
    $gebruikers[] = $gebruikerRow;

  }}





}
catch(Exception $e){
  echo $e;
}
?>
<html>
<head>
<title>
  Admin WXNDER
</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script  src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

 <!--<script  src="//code.jquery.com/jquery-1.12.4.js"></script>-->
  <script async src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link href="<?php echo get_template_directory_uri(); ?>/img/favico.png" rel="shortcut icon">
<!-- <link href="<?php echo get_template_directory_uri(); ?>/img/icons/touch.png" rel="apple-touch-icon-precomposed"> -->
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.green-teal.min.css">
<script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=PT+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300&display=swap" rel="stylesheet">
<link href="<?php echo get_template_directory_uri(); ?>/css/admin.css" rel="stylesheet">
<script src='<?php echo get_template_directory_uri(); ?>/js/admin.js'></script>

</head>
<body>
  <div id='msg'>
    <?php
    if($resultNewQuery === TRUE) {
      echo "Record updated successfully";
    } else if($newQuery) {
      echo "Error updating record: " . $mysqli->error;
    }
     ?>
  </div>
  <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
  <header class="mdl-layout__header">
    <a href="/admin" style="border-bottom:2px solid orange;">BESTELLINGEN</a>
    <!-- <a href="/productietijden">PRODUCTIETIJDEN</a> -->
    <a href="/levertijden">LEVERTIJDEN</a>
    <a href="/voorraad">VOORRAAD</a>
    <a href="/voorraadgroepen">VOORRAADGROEPEN</a>
    <a href="/productactiviteit">PRODUCTACTIVITEIT</a>

  </header>
  <main class="mdl-layout__content">

      <div class="page-content">
        <div id='bestellingen-pipeline'>
          <div id='pipeline-stage-1' class='pipeline-stage'>
            <header>
            <h3 class='stage-title'>Besteld</h3>
          </header>

          <div class='innerstage'>
          <?php
            if($resultBestellingen->num_rows > 0){
              while($row = $resultBestellingen->fetch_assoc()){
                $ID = $row["ID"];
                $type = $row["Product"];
                $productSpec = $row["ProductSpecificatie"];
                $gebruikerID = $row["GebruikerID"];
                $opmerkingen = "";
                $prijs = $row["Prijs"];
                $leverAdres = $row['LeverAdres'];
                $leverStad = $row['LeverStad'];
                $leverPostcode = $row['LeverPostcode'];
                $aangemaakt = $row['Aangemaakt'];

                $leverDatumID = $row['LeverdatumID'];
                $leverDatum = '';
                if(substr($leverDatumID,0,2) == '3S'){
                  $leverDatum ="POSTNL ($leverDatumID)";
                }
                else{
                  $findRow = array_search($leverDatumID, array_column($leverdata, 'ID'));
                  if($findRow > -1){
                    $leverRow = $leverdata[$findRow];
                    $leverDatum = $leverRow["Datum"]." ".$leverRow["Begintijd"]." tot ".$leverRow["Eindtijd"];
                  }
                }
                $gekozenLevering = $row['GekozenLevering'];


                $betaald = $row['Betaald'];
                $geproduceerd = $row['Geproduceerd'];
                $geleverd = $row['Geleverd'];

                if(!$betaald || $betaald == "0000-00-00 00:00:00"){
                  $betaald = "";
                }
                if(!$geproduceerd ||$geproduceerd == "0000-00-00 00:00:00"){
                  $geproduceerd = "";
                }
                if(!$geleverd || $geleverd ==  "0000-00-00 00:00:00"){
                  $geleverd = "";
                }
                if(!$leverDatum || $leverDatum ==  "0000-00-00 00:00:00"){
                  $leverDatum = "";
                }

                $route = '';
                foreach($gebruikers as $gebruikerRow){
                    if($gebruikerRow["ID"] == $gebruikerID){
                      $naam = $gebruikerRow["Voornaam"]." ".$gebruikerRow["Achternaam"];
                      $email = $gebruikerRow["Email"];
                      $telefoonnummer = $gebruikerRow["Telefoonnummer"];
                      if(!$leverAdres){
                        $leverAdres = $gebruikerRow["Adres"];
                      }
                      if(!$leverPostcode){
                        $leverPostcode = $gebruikerRow["Postcode"];
                      }
                      if(!$leverStad){
                        $leverStad = $gebruikerRow["Stad"];
                      }
                      break;
                    }

                }

                  echo "<div class='bestelling box'>";
                  echo "<p class='prijs'>".$prijs."</p> \n";
                  echo "<p class='type'>".$type."</p> \n";
                  echo "<p class='naam'>".$naam."</p> \n";
                  echo "<p class='ID'>$ID</p> \n";
                  echo "<div class='specificaties'> \n";

                echo "</div>";
                // echo "<p class='route'>Route: ".$route."</p> \n";
                echo "<div class='adresgegevens'> \n";
                echo "<p class='adres'>".$leverAdres."</p><br> \n";
                echo "<p class='postcode'>".$leverPostcode."</p> \n";
                echo "<p class='stad'>".$leverStad."</p> \n";
                echo "</div>";
                echo "<p class='aangemaakt'>".$aangemaakt."</p> \n";
                echo "<div class='details'>";
                echo "<p class='email'>Email: ".$email."</p> \n";
                echo "<p class='telefoonnummer'>Tel: ".$telefoonnummer."</p> \n";
                echo "<p class='email'>Specificaties: ".$productSpec."</p> \n";
                echo "<p class='betaald'>Betaald: ".$betaald."</p> \n";
                echo "<p class='gekozenlevering'>Gekozen Levering: ".$gekozenLevering."</p> \n";
                echo "<p class='leverdatum'>Leverdatum: ".$leverDatum."</p> \n";
                echo "<p class='geproduceerd'>Geproduceerd: ".$geproduceerd."</p> \n";
                echo "<p class='geleverd'>Geleverd: ".$geleverd."</p> \n";

                echo "</div>";
                echo "</div>";
              }
            }


           ?>

         </div>
          </div>
          <div id='pipeline-stage-2' class='pipeline-stage'>
            <header>
            <h3 class='stage-title'>Betaald</h3>
          </header>

          <div class='innerstage'>
          </div>
        </div>
          <div id='pipeline-stage-3' class='pipeline-stage'>
            <header>
            <h3 class='stage-title'>Gepland</h3>
          </header>

          <div class='innerstage'>
          </div>
          </div>
          <div id='pipeline-stage-4' class='pipeline-stage'>
            <header>
            <h3 class='stage-title'>Gemaakt</h3>
          </header>

          <div class='innerstage'>
          </div>
          </div>
          <div id='pipeline-stage-5' class='pipeline-stage'>
            <header>
            <h3 class='stage-title'>Geleverd</h3>
          </header>

          <div class='innerstage'>
          </div>
          </div>
        </div>

        <div id='bestelling'>

            <i class="material-icons" id='bestellingBack'>arrow_back</i>
            <div id='inner'></div>

            <!-- <form method=post class='bestelling-form' id='productiedatum-form'>
              <p>Productiedatum</p><select name='input-productiedatum'>
                <option value='verwijderen'>Waarde verwijderen</option>
                <?php
                foreach($productietijden as $row){
                    $text = $row['Datum']." ".$row['Begintijd']." tot ".$row['Eindtijd']." (".$row['ID'].")";
                    echo "<option value='".$text."'>".$text."</option>";
                  }

                 ?>
               </select><br><br>
               <input type='submit' name='productie-submit' value='Opslaan'>
            </form> -->
            <form method=post class='bestelling-form' id='leverdatum-postnl-form'>
              <p>Leverdatum POSTNL</p>
              <input type='text' name='trackandtrace' placeholder="Track&Trace Code">
              <input type='text' name='hiddenID' style="display:none;" id='hiddenID'>
              <br>

              <input type="checkbox" name='email'>  Mail sturen<br>
               <input type='submit' name='lever-submit-postnl' value='Opslaan'>
            </form>

            <form method=post class='bestelling-form' id='leverdatum-form'>
              <p>Leverdatum</p>
              <select name='input-leverdatum'>

                <option value='verwijderen'>Verwijderen</option>
                <?php
                  foreach($leverdata as $row){
                    $text = $row['Datum']." ".$row['Begintijd']." tot ".$row['Eindtijd'];
                    $id = $row['ID'];
                    echo "<option value='$id'>$text</option>";
                  }
                 ?>
               </select><br>

                <input type="checkbox" name='email'>  Mail sturen<br>
               <input type='submit' name='lever-submit' value='Opslaan'>
            </form>
            <form method=post class='bestelling-form' id='geproduceerd-form'>
              <p>Geproduceerd</p><select name='input-geproduceerddatum' id='input-geproduceerddatum'>
                <option value='nu'>Nu</option>
                <option value='verwijderen'>Waarde verwijderen</option>
               </select><br><br>
               <input type='submit' name='geproduceerd-submit' value='Opslaan' id='geproduceerd-submit'>
            </form>
            <form method=post class='bestelling-form' id='geleverd-form'>
              <p>Geleverd</p><select name='input-geleverddatum' id='input-geleverddatum'>
                <option value='nu'>Nu</option>
                <option value='verwijderen'>Waarde verwijderen</option>
               </select><br><br>
               <input type='submit' name='geleverd-submit' value='Opslaan' id='geleverd-submit'>
            </form>

          </div>
        </div>



      </div>

  </main>











</div>




</body>



</html>
