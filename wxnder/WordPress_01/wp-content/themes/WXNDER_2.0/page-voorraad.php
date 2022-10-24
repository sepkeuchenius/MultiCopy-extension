<?php
$newQuery = false;
$multi = false;
if(isset($_POST['voorraad-submit'])){
  $names = ['ID', 'GroepID', 'GroepPrio','Naam', 'Details', 'Leverancier', 'Huidige-voorraad', 'Alarmgrens', 'Levertijd', 'Verkocht', 'Gemuteerd', 'Inprijs', 'Uitprijs', 'BTW', 'Bestelbaar'];
  $nameslist = implode(array_map(function($el){return '`'.$el.'`';}, $names), ',');
  $valueslist =array_map(function($el){if($_POST[$el]){return "'".$_POST[$el]."'";}else{return '""';}}, $names);
  // $valueslist[0] = "'".rand()."'";
  $valuesstring = implode($valueslist, ',');
  $newQuery = "INSERT INTO Voorraad ($nameslist)
  VALUES ($valuesstring); ";
  $newQuery .="INSERT INTO `Productactiviteit` (`ID`) VALUES ($valueslist[0])";
  $multi= true;
}
if(isset($_POST['action-submit'])){
  $action = $_POST['action'];
  $id = $_POST['id'];
  if($action == 'delete'){
    $newQuery = 'DELETE FROM Voorraad WHERE `ID`='.$id;
  }

}
if(isset($_POST['edit-submit'])){
  $id = $_POST['ID_old'];
  $names = ['ID', 'GroepID', 'GroepPrio','Naam', 'Details', 'Leverancier', 'Huidige-voorraad', 'Alarmgrens', 'Levertijd', 'Verkocht', 'Gemuteerd', 'Inprijs', 'Uitprijs', 'BTW', 'Bestelbaar'];
  $newQuery = 'UPDATE `Voorraad` SET ';
  $cols = '';
  $values = '';
  foreach($names as $name){
    if($_POST[$name] and strlen($_POST[$name]) > 0){
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
  if($multi){
    $resultNewQuery =  $mysqli->multi_query($newQuery);
  }
  else{
  $resultNewQuery =  $mysqli->query($newQuery);
}}
$msg = FALSE;
if($resultNewQuery === TRUE) {
  $msg = "Record updated successfully";
} else if($newQuery) {
  $msg = "Error updating record: " . $mysqli->error;
}

$queryBestellingen = "SELECT * FROM `Bestellingen`";
$queryGebruikers = "SELECT * FROM `Gebruikers`";
$queryBoxSpecificaties = "SELECT * FROM `BoxSpecificaties`";
$queryGoSpecificaties = "SELECT * FROM `GoSpecificaties`";
$queryValentijnSpecificaties = "SELECT * FROM `ValentijnSpecificaties`";
$queryLevertijden = "SELECT * FROM `Levertijden`";
$queryProductietijden = "SELECT * FROM `Productietijden`";
$queryVoorraad = "SELECT * FROM `Voorraad` ORDER BY `ID`";
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




$voorraadgroepen = array();
if( $resultVoorraadgroepen->num_rows > 0){
  while ($groeprow = $resultVoorraadgroepen->fetch_assoc()) {
    // echo $leverRow['ID'];
    $voorraadgroepen[] = $groeprow;
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
  Voorraad WXNDER
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
    if($msg){
      echo $msg;
    }
     ?>
  </div>
  <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
  <header class="mdl-layout__header">
    <a href="/admin" >BESTELLINGEN</a>
    <!-- <a href="/productietijden" >PRODUCTIETIJDEN</a> -->
    <a href="/levertijden" >LEVERTIJDEN</a>
    <a href="/voorraad" style="border-bottom:2px solid orange;">VOORRAAD</a>
    <a href="/voorraadgroepen" >VOORRAADGROEPEN</a>
    <a href="/productactiviteit">PRODUCTACTIVITEIT</a>


  </header>
  <main class="mdl-layout__content">

      <div class="page-content">
        <div id="content">
          <div id='voorraad'>
        <table class="mdl-data-table mdl-js-data-table  mdl-shadow--2dp" id='voorraadtabel'>
        <thead>
          <tr>
            <th>ID</th>
            <!-- <th>GroepID</th> -->
            <!-- <th>GroepPrio</th> -->
            <th>Naam</th>
            <th>Details</th>
            <th>Leverancier</th>
            <th>Huidige</th>
            <th>Alarm</th>
            <th>Levertijd</th>
            <th>Verkocht</th>
            <th>Gemuteerd</th>
            <th>Inprijs</th>
            <th>Uitprijs</th>
            <th>BTW</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
          $edit = '<td><i class="material-icons" id="leverdatumBack" onclick="edit(this)">edit</i></td>';
          $delete = '<td><i class="material-icons" id="leverdatumBack" onclick="deleteProductie(this)">delete</i></td>';
          if($resultVoorraad->num_rows > 0){
            while($row = $resultVoorraad->fetch_assoc()){
              $ID = "<td class='td_ID'>".$row["ID"]."</td>";
              $GroepID = "<td class='td_GroepID'>".$row["GroepID"]."</td>";


              $GroepPrio = "<td class='td_GroepPrio'>".$row["GroepPrio"]."</td>";
              $Naam = "<td class='td_Naam'>".$row["Naam"]."</td>";
              $Details = "<td class='td_Details'>".$row["Details"]."</td>";
              $Leverancier = "<td class='td_Leverancier'>".$row["Leverancier"]."</td>";
              $Huidigevoorraad = "<td class='td_Huidige-voorraad'>".$row["Huidige-voorraad"]."</td>";
              $Alarmgrens = "<td class='td_Alarmgrens'>".$row["Alarmgrens"]."</td>";
              $Levertijd = "<td class='td_Levertijd'>".$row["Levertijd"]."</td>";
              $Verkocht = "<td class='td_Verkocht'>".$row["Verkocht"]."</td>";
              $Gemuteerd = "<td class='td_Gemuteerd'>".$row["Gemuteerd"]."</td>";
              $Inprijs = "<td class='td_Inprijs'>".$row["Inprijs"]."</td>";
              $Uitprijs = "<td class='td_Uitprijs'>".$row["Uitprijs"]."</td>";
              $BTW = "<td class='td_BTW'>".$row["BTW"]."</td>";

              $text = $ID.$Naam.$Details.$Leverancier.$Huidigevoorraad.$Alarmgrens.$Levertijd.$Verkocht.$Gemuteerd.$Inprijs.$Uitprijs.$BTW.$edit.$delete;
              echo "<tr>".$text."</tr>";
            }
          }
             ?>



        </tbody>
      </table>
      <button class="mdl-button mdl-js-button" onclick='nieuwProduct()'>Nieuw product</button>
    </div>

      <div id='nieuw-product'>
        <i class="material-icons" id='productBack' onclick='productTerug()'>arrow_back</i>
        <br>
      <form method=post class='bestelling-form' id='nieuwproduct-form'>
        <p>Nieuw Product</p><br>
        <div class='form-item'>
        <span>ID</span>
        <input type='number' name="ID" placeholder="ID"><br>
      </div>
        <div class='form-item'>
        <span>Naam</span>
        <input type='text' name="Naam" placeholder="Naam"><br>
      </div>
      <div class='form-item'>
      <span>Details</span>
      <input type='text' name="Details" placeholder="Naam"><br>
    </div>
        <div class='form-item'>
          <span>Leverancier</span>
        <input type='text' name="Leverancier" placeholder="Leverancier"><br>
      </div>
        <div class='form-item'>
          <span>Huidige voorraad</span>
        <input type='number' name="Huidige-voorraad" placeholder="Huidige voorraad" value=0><br>
      </div>
        <div class='form-item'>
          <span>Alarmgrens</span>
        <input type='number' name="Alarmgrens" placeholder="Alarmgrens" value=0><br>
      </div>
        <div class='form-item'>
          <span>Levertijd (dagen)</span>
        <input type='number' name="Levertijd" placeholder="Levertijd" value=0><br>
      </div>
        <div class='form-item'>
          <span>Verkocht</span>
        <input type='number' name="Verkocht" placeholder="Verkocht" value=0><br>
      </div>
        <div class='form-item'>
          <span>Gemuteerd</span>
        <input type='number' name="Gemuteerd" placeholder="Gemuteerd" value=0><br>
      </div>
        <div class='form-item'>
          <span>Inprijs</span>
        <input type='number' name="Inprijs" placeholder="Inprijs" value=0><br>
      </div>
        <div class='form-item'>
          <span>Uitprijs</span>
        <input type='number' name="Uitprijs" placeholder="Uitprijs" value=0><br>
      </div>
        <div class='form-item'>
          <span>BTW</span>
        <input type='number' name="BTW" placeholder="BTW" value=9><br>
      </div>

      <div class='form-item'>
        <span>Voorraadgroep</span>
          <select name='GroepID'>
            <option value='0'>Geen groep</option>
            <?php
              foreach($voorraadgroepen as $groep){
                echo "<option value='".$groep['ID']."'>".$groep['ID'].": ".$groep['Naam']."</option>";
              }
             ?>
           </select>
           </div>
           <div class='form-item'>
               <span>Groepprio (1-10)</span>
             <input type='number' name="Bestelbaar" placeholder="" value=1><br>
           </div>
           <div class='form-item'>
             <span><br></span>
             <input type='submit' name='voorraad-submit' value='Toevoegen'>
           </div>
      </form>
      <form class='hiddenform' method='post'>
        <input type='text' name='action' id='action'>
        <input type='text' name='id' id='id'>
        <input type='submit' name='action-submit' id='action-submit'>
      </form>

    </div>
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
