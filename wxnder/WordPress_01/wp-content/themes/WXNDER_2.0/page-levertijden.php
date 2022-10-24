<?php
$newQuery = false;
if(isset($_POST['levering-submit'])){
  $ID = rand();
  $datum = '"'.$_POST['leverdatum-nieuw'].'"';
  $begin = '"'.$_POST['leverbegintijd-nieuw'].'"';
  $eind =  '"'.$_POST['levereindtijd-nieuw'].'"';
  $max =  '"'.$_POST['max-nieuw'].'"';
  $koerier =  '"'.$_POST['koerier'].'"';
  $uiterste = '"'.$_POST['uiterste-nieuw']." ".$_POST['uiterstetijd-nieuw'].'"';
  $newQuery = "INSERT INTO Levertijden (ID, Datum, Begintijd, Eindtijd, `Max-Leveringen`, `Uiterste-bestelling`, Koerier)
  VALUES ($ID, $datum, $begin, $eind, $max, $uiterste,$koerier)";
}
if(isset($_POST['action-submit'])){
  $action = $_POST['action'];
  $id = $_POST['id'];
  if($action == 'delete'){
    $newQuery = 'DELETE FROM Levertijden WHERE `ID`='.$id;
  }

}
if(isset($_POST['edit-submit'])){
  $id = $_POST['ID'];
  $names = ['ID', 'Datum', 'Begintijd', 'Eindtijd',  'Max-leveringen', 'Huidige-leveringen','Uiterste-bestelling', 'Koerier'];
  $newQuery = 'UPDATE `Levertijden` SET ';
  $cols = '';
  $values = '';
  foreach($names as $name){
    if($_POST[$name] and strlen($_POST[$name]) > 0 and $name != 'ID'){
      $newQuery = $newQuery."`".$name."` = "."'".$_POST[$name]."' , ";
    }
  }
  $newQuery = substr($newQuery ,0, -2);
  $newQuery = $newQuery." WHERE ID=".$id;
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
$queryBestellingen = "SELECT * FROM `Bestellingen`";
$queryGebruikers = "SELECT * FROM `Gebruikers`";
$queryBoxSpecificaties = "SELECT * FROM `BoxSpecificaties`";
$queryGoSpecificaties = "SELECT * FROM `GoSpecificaties`";
$queryValentijnSpecificaties = "SELECT * FROM `ValentijnSpecificaties`";
$queryLevertijden = "SELECT * FROM `Levertijden` ORDER BY `Datum`, `Begintijd`";
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



}
catch(Exception $e){
  echo $e;
}
?>
<html>
<head>
<title>
  Levertijden WXNDER
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
    <a href="/admin" >BESTELLINGEN</a>
    <!-- <a href="/productietijden" >PRODUCTIETIJDEN</a> -->
    <a href="/levertijden" style="border-bottom:2px solid orange;">LEVERTIJDEN</a>
    <a href="/voorraad">VOORRAAD</a>
    <a href="/voorraadgroepen">VOORRAADGROEPEN</a>
    <a href="/productactiviteit">PRODUCTACTIVITEIT</a>


  </header>
  <main class="mdl-layout__content">

      <div class="page-content">
        <div id="content">
          <div id='leverdata'>
        <table class="mdl-data-table mdl-js-data-table  mdl-shadow--2dp" id='levertabel'>
        <thead>
          <tr>
            <th>ID</th>
            <th>Datum</th>
            <th>Begintijd</th>
            <th>Eindtijd</th>
            <th>Max</th>
            <th>Huidig</th>
            <th>Uiterste besteldatum</th>
            <th>Koerier</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php

          $edit = '<td><i class="material-icons" id="leverdatumBack" onclick="edit(this)">edit</i></td>';
          $delete = '<td><i class="material-icons" id="leverdatumBack" onclick="deleteProductie(this)">delete</i></td>';

          if($resultLevertijden->num_rows > 0){
            while($row = $resultLevertijden->fetch_assoc()){
              $ID = "<td class='td_ID'>".$row["ID"]."</td>";
              $datum = "<td class='td_Datum'>".$row["Datum"]."</td>";
              $begin = "<td class='td_Begintijd'>".$row["Begintijd"]."</td>";
              $eind = "<td class='td_Eindtijd'>".$row["Eindtijd"]."</td>";
              $max = "<td class='td_Max-leveringen'>".$row["Max-leveringen"]."</td>";
              $huidig = "<td class='td_Huidige-leveringen'>".$row["Huidige-leveringen"]."</td>";
              $uiterst = "<td class='td_Uiterste-bestelling'>".$row["Uiterste-bestelling"]."</td>";
              $Koerier = "<td class='td_Koerier'>".$row["Koerier"]."</td>";
              $text = $ID.$datum.$begin.$eind.$max.$huidig.$uiterst.$Koerier.$edit.$delete;
              echo "<tr>".$text."</tr>";
            }
          }
             ?>



        </tbody>
      </table>
      <button class="mdl-button mdl-js-button" onclick='nieuweLeverdatum()'>Nieuwe leverdatum</button>
    </div>

      <div id='nieuwe-leverdatum'>
        <i class="material-icons" id='leverdatumBack' onclick='leverdatumTerug()'>arrow_back</i>
        <br>
      <form method=post class='bestelling-form' id='nieuweleverdatum-form'>
        <p>Nieuwe datum</p><br>
        <input type='date' name='leverdatum-nieuw'>
        <input type='time' name='leverbegintijd-nieuw'> tot
        <input type='time' name='levereindtijd-nieuw'><br><br>
        <input type='number' name='max-nieuw' placeholder="Max" width="30px"><br><br>
        Uiterste Besteldatum:<br>
        <input type='date' name='uiterste-nieuw' placeholder="">
        om <input type='time' name='uiterstetijd-nieuw' placeholder="">
        <br><br>
        <input type='text' name='koerier' placeholder="Koerier">

        <br><br>
         <input type='submit' name='levering-submit' value='Toevoegen'>
      </form>
    </div>
    <form class='hiddenform' method='post'>
      <input type='text' name='action' id='action'>
      <input type='text' name='id' id='id'>
      <input type='submit' name='action-submit' id='action-submit'>
    </form>
    <div id='edit-product'>
      <i class="material-icons" id='productBack' onclick='editTerug()'>arrow_back</i>
    <form class="editform bestelling-form" id='editform' style="display: none;" method='post'>

    </form>

    </div>

    </div>
      </div>

  </main>











</div>




</body>



</html>
