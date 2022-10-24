<?php
$newQuery = false;
if(isset($_POST['activiteit-submit'])){
  $names = ['ID', 'GroepID', 'GroepPrio','Naam', 'Details', 'Leverancier', 'Huidige-voorraad', 'Alarmgrens', 'Levertijd', 'Verkocht', 'Gemuteerd', 'Inprijs', 'Uitprijs', 'BTW', 'Bestelbaar'];
  $nameslist = implode(array_map(function($el){return '`'.$el.'`';}, $names), ',');
  echo $_POST['Bestelbaar'];
  $valueslist =array_map(function($el){if($_POST[$el]){return "'".$_POST[$el]."'";}else{return '""';}}, $names);
  $valueslist[0] = "'".rand()."'";
  $valuesstring = implode($valueslist, ',');
  $newQuery = "INSERT INTO Productactiviteit ($nameslist)
  VALUES ($valuesstring)";
}
if(isset($_POST['action-submit'])){
  $action = $_POST['action'];
  $id = $_POST['id'];
  if($action == 'delete'){
    $newQuery = 'DELETE FROM Productactiviteit WHERE `ID`='.$id;
  }
  else if($action =='remove-box-actief'){
    $newQuery = 'UPDATE Productactiviteit SET `BOX-Actief` = 0 WHERE `ID`='.$id;
  }
  else if($action =='add-box-actief'){
    $newQuery = 'UPDATE Productactiviteit SET `BOX-Actief` = 1 WHERE `ID`='.$id;
  }
  else if($action =='remove-go-actief'){
    $newQuery = 'UPDATE Productactiviteit SET `GO-Actief` = 0 WHERE `ID`='.$id;
  }
  else if($action =='add-go-actief'){
    $newQuery = 'UPDATE Productactiviteit SET `GO-Actief` = 1 WHERE `ID`='.$id;
  }


}
if(isset($_POST['edit-submit'])){
  $id = $_POST['ID'];
  $names = ['Ondergrens', 'BOX-Beschrijving', 'GO-Beschrijving'];
  $newQuery = 'UPDATE `Productactiviteit` SET ';
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
$queryLevertijden = "SELECT * FROM `Levertijden`";
$queryProductietijden = "SELECT * FROM `Productietijden`";
$queryVoorraad = "SELECT * FROM `Voorraad`";
$queryProductactiviteit = "SELECT * FROM `Productactiviteit` ORDER BY `ID`";
$queryVoorraadgroepen = "SELECT * FROM `Voorraadgroepen`";

$resultBestellingen = $mysqli->query($queryBestellingen);
$resultGebruikers = $mysqli->query($queryGebruikers);
$resultBoxSpecificaties = $mysqli->query($queryBoxSpecificaties);
$resultGoSpecificaties = $mysqli->query($queryGoSpecificaties);
$resultValentijnSpecificaties = $mysqli->query($queryValentijnSpecificaties);
$resultLevertijden = $mysqli->query($queryLevertijden);
$resultProductietijden = $mysqli->query($queryProductietijden);
$resultVoorraad = $mysqli->query($queryVoorraad);
$resultProductactiviteit = $mysqli->query($queryProductactiviteit);
$resultVoorraadgroepen = $mysqli->query($queryVoorraadgroepen);




$voorraadgroepen = array();
if( $resultVoorraadgroepen->num_rows > 0){
  while ($groeprow = $resultVoorraadgroepen->fetch_assoc()) {
    // echo $leverRow['ID'];
    $voorraadgroepen[] = $groeprow;
  }
}
$producten = array();
if( $resultVoorraad->num_rows > 0){
  while ($groeprow = $resultVoorraad->fetch_assoc()) {
    // echo $leverRow['ID'];
    $producten[] = $groeprow;
  }
}


}
catch(Exception $e){
  echo $e;
}
?>
<html>
<head>
<title>
  Productactiviteit WXNDER
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
    <a href="/levertijden" >LEVERTIJDEN</a>
    <a href="/voorraad" >VOORRAAD</a>
    <a href="/voorraadgroepen" >VOORRAADGROEPEN</a>
    <a href="/productactiviteit" style="border-bottom:2px solid orange;">PRODUCTACTIVITEIT</a>


  </header>
  <main class="mdl-layout__content">

      <div class="page-content">
        <div id="content">
          <div id='voorraad'>
        <table class="mdl-data-table mdl-js-data-table  mdl-shadow--2dp" id='voorraadtabel'>
        <thead>
          <tr>
            <th>ID</th>
            <th>Naam</th>
            <th>Ondergrens</th>
            <th>BOX-Actief</th>
            <th>BOX-Beschrijving</th>
            <th>GO-Actief</th>
            <th>GO-Beschrijving</th>

            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php

          $edit = '<td><i class="material-icons" id="leverdatumBack" onclick="editProductactiviteit(this)">edit</i></td>';
          $delete = '<td><i class="material-icons" id="leverdatumBack" onclick="deleteProductie(this)">delete</i></td>';

          if($resultProductactiviteit->num_rows > 0){
            while($row = $resultProductactiviteit->fetch_assoc()){
              foreach($producten as $product){
                if($product['ID'] == $row['ID']){
                  $naam = $product['Naam'];
                  break;
                }
              }

              $ID = "<td class='td_ID'>".$row["ID"]."</td>";
              $Naam = "<td class='td_Naam'>".$naam."</td>";
              $Ondergrens = "<td class='td_Ondergrens'>".$row["Ondergrens"]."</td>";
              if($row['BOX-Actief'] === '1'){
                $boxactief = "checked";
              }
              else{
                $boxactief = "";
              }
              $BoxActief = "<td class='td_X'><input type='checkbox' class='box-actief' ".$boxactief."></td>";
              if($row['GO-Actief'] === '1'){
                $goactief = "checked";
              }
              else{
                $goactief = "";
              }
              $BoxBeschrijving = "<td class='td_BOX-Beschrijving'>".$row["BOX-Beschrijving"]."</td>";
              $GoActief =  "<td class='td_X'><input type='checkbox' class='go-actief' ".$goactief."></td>";
              $GoBeschrijving = "<td class='td_GO-Beschrijving'>".$row["GO-Beschrijving"]."</td>";

              $text = $ID.$Naam.$Ondergrens.$BoxActief.$BoxBeschrijving.$GoActief.$GoBeschrijving.$edit.$delete;
              echo "<tr>".$text."</tr>";
            }
          }
             ?>



        </tbody>
      </table>
    </div>

      <div id='nieuw-product'>
        <i class="material-icons" id='productBack' onclick='productTerug()'>arrow_back</i>
        <br>
          <form class='hiddenform' method='post'>
        <input type='text' name='action' id='action'>
        <input type='text' name='id' id='id'>
        <input type='submit' name='action-submit' id='action-submit'>
      </form>

    </div>
    <div id='edit-product'>
      <i class="material-icons" id='productBack' onclick='editTerug()'>arrow_back</i>
    <form class="editform bestelling-form" id='editform' style="display: none;" method='post'>
      <div class='form-item' style="display:none;">
        <span>ID</span>
        <input type='text' name='ID' id='ID'>
      </div>

      <div class='form-item'>
        <span>Ondergrens</span>
        <input type='text' name='Ondergrens' id='Ondergrens'>
      </div>
      <div class='form-item'>
        <span>BOX-Beschrijving</span>
        <input type='text' name='BOX-Beschrijving'id='BOX-Beschrijving'>
      </div>
      <div class='form-item'>
        <span>GO-Beschrijving</span>
        <input type='text' name='GO-Beschrijving'id='GO-Beschrijving'>
      </div>

      <input type='submit' name='edit-submit'>
      </div>
    </form>

    </div>
    </div>
      </div>

  </main>











</div>




</body>



</html>
