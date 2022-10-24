<?php
$newQuery = false;
$multi = false;
if(isset($_POST['voorraad-submit'])){
    $newQuery= '';
    $FromID = '"'.$_POST['ID'].'"';
    for($i = 1; $i<11; $i++){
      $SubID = '"'.$_POST["SubID-$i"].'"';
      $Aantal = '"'.$_POST["Aantal-$i"].'"';
      if($_POST["Aantal-$i"] > 0){
        $multi = true;
        $ID = '"'.rand().'"';
        $newQuery .= "INSERT INTO Voorraadgroepen (ID,FromID,SubID,  Aantal)
        VALUES ($ID, $FromID,$SubID,$Aantal);";
      }
  }
}
if(isset($_POST['action-submit'])){
  $action = $_POST['action'];
  $fromid = $_POST['fromid'];
  $id = $_POST['id'];
  $subid = $_POST['subid'];
  if($action == 'delete'){
    $newQuery = "DELETE FROM Voorraadgroepen WHERE `ID` = $id";
  }

}
if(isset($_POST['edit-submit'])){
  $id = $_POST['ID'];
  $FromID = $_POST['FromID'];
  $names = ['FromID', 'SubID', 'Aantal'];
  $newQuery = 'UPDATE `Voorraadgroepen` SET ';
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
  }

}
$queryBestellingen = "SELECT * FROM `Bestellingen`";
$queryGebruikers = "SELECT * FROM `Gebruikers`";
$queryBoxSpecificaties = "SELECT * FROM `BoxSpecificaties`";
$queryGoSpecificaties = "SELECT * FROM `GoSpecificaties`";
$queryValentijnSpecificaties = "SELECT * FROM `ValentijnSpecificaties`";
$queryLevertijden = "SELECT * FROM `Levertijden`";
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

$queryVoorraad = "SELECT * FROM `Voorraad`";
$resultVoorraad = $mysqli->query($queryVoorraad);

$voorraad = array();
if( $resultVoorraad->num_rows > 0){
  while ($row = $resultVoorraad->fetch_assoc()) {
    $voorraad[] = $row;
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
  Voorraadgroepen WXNDER
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
    <a href="/voorraadgroepen" style="border-bottom:2px solid orange;">VOORRAADGROEPEN</a>
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
            <th>FromID</th>
            <th>Naam</th>
            <th>SubID</th>
            <th>SubNaam</th>
            <th>Aantal</th>

            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php

          $edit = '<td><i class="material-icons" id="leverdatumBack" onclick="editVoorraadgroep(this)">edit</i></td>';
          $delete = '<td><i class="material-icons" id="leverdatumBack" onclick="deleteVoorraadgroep(this)">delete</i></td>';

          if($resultVoorraadgroepen->num_rows > 0){
            while($row = $resultVoorraadgroepen->fetch_assoc()){
              $ID = "<td class='td_ID'>".$row["ID"]."</td>";
              $FromID = "<td class='td_FromID'>".$row["FromID"]."</td>";

              $productRow = array_search($row['FromID'], array_column($voorraad, 'ID'));
              $naam = $voorraad[$productRow]["Naam"];

              $Naam = "<td class='td_X'>".$naam."</td>";
              $SUBID = "<td class='td_SubID'>".$row["SubID"]."</td>";

              $SubproductRow = array_search($row['SubID'], array_column($voorraad, 'ID'));
              $subnaam = $voorraad[$SubproductRow]["Naam"];
              $SubNaam = "<td class='td_X'>".$subnaam."</td>";

              $Aantal = "<td class='td_Aantal'>".$row["Aantal"]."</td>";


              $text = $ID.$FromID.$Naam.$SUBID.$SubNaam.$Aantal.$edit.$delete;
              echo "<tr>".$text."</tr>";
            }
          }
             ?>



        </tbody>
      </table>
      <button class="mdl-button mdl-js-button" onclick='nieuwProduct()'>Nieuwe link</button>
    </div>

      <div id='nieuw-product'>
        <i class="material-icons" id='productBack' onclick='productTerug()'>arrow_back</i>
        <br>
      <form method=post class='bestelling-form' id='nieuwproduct-form'>
        <p>Nieuwe links</p><br>
        <div class="form-item">
          <span>Product</span>
        <select name='ID' id='ID'>
        <?php
          foreach($voorraad as $product){
            echo "<option value='".$product['ID']."'>".$product['ID'].": ".$product["Naam"]."</option>";
          }
        ?></select>
      </div>
      <div class="sub-products">
          <div class="form-item">
            <span>SubProduct</span>
            <select name='SubID-1' id='SubID-1'>
            <?php
              foreach($voorraad as $product){
                echo "<option value='".$product['ID']."'>".$product['ID'].": ".$product["Naam"]."</option>";
              }
            ?></select>
          </div>
          <div class="form-item">
            <span>Aantal</span>
            <input type='number' name='Aantal-1' id='Aantal-1' value=0></select>
          </div>
          <br>


          <div class="form-item">
            <span>SubProduct</span>
            <select name='SubID-2' id='SubID-2'>
            <?php
              foreach($voorraad as $product){
                echo "<option value='".$product['ID']."'>".$product['ID'].": ".$product["Naam"]."</option>";
              }
            ?></select>
          </div>
          <div class="form-item">
            <span>Aantal</span>
            <input type='number' name='Aantal-2' id='Aantal-2' value=0></select>
          </div>
          <br>


          <div class="form-item">
            <span>SubProduct</span>
            <select name='SubID-3' id='SubID-3'>
            <?php
              foreach($voorraad as $product){
                echo "<option value='".$product['ID']."'>".$product['ID'].": ".$product["Naam"]."</option>";
              }
            ?></select>
          </div>
          <div class="form-item">
            <span>Aantal</span>
            <input type='number' name='Aantal-3' id='Aantal-3' value=0></select>
          </div>

          <br>

          <div class="form-item">
            <span>SubProduct</span>
            <select name='SubID-4' id='SubID-4'>
            <?php
              foreach($voorraad as $product){
                echo "<option value='".$product['ID']."'>".$product['ID'].": ".$product["Naam"]."</option>";
              }
            ?></select>
          </div>
          <div class="form-item">
            <span>Aantal</span>
            <input type='number' name='Aantal-4' id='Aantal-4' value=0></select>
          </div>
          <br>

          <div class="form-item">
            <span>SubProduct</span>
            <select name='SubID-5' id='SubID-5'>
            <?php
              foreach($voorraad as $product){
                echo "<option value='".$product['ID']."'>".$product['ID'].": ".$product["Naam"]."</option>";
              }
            ?></select>
          </div>
          <div class="form-item">
            <span>Aantal</span>
            <input type='number' name='Aantal-5' id='Aantal-5' value=0></select>
          </div>
          <br>

            <div class="form-item">
              <span>SubProduct</span>
              <select name='SubID-6' id='SubID-6'>
              <?php
                foreach($voorraad as $product){
                  echo "<option value='".$product['ID']."'>".$product['ID'].": ".$product["Naam"]."</option>";
                }
              ?></select>
            </div>
            <div class="form-item">
              <span>Aantal</span>
              <input type='number' name='Aantal-6' id='Aantal-6' value=0></select>
            </div>
            <br>


          <div class="form-item">
            <span>SubProduct</span>
            <select name='SubID-7' id='SubID-7'>
            <?php
              foreach($voorraad as $product){
                echo "<option value='".$product['ID']."'>".$product['ID'].": ".$product["Naam"]."</option>";
              }
            ?></select>
          </div>
          <div class="form-item">
            <span>Aantal</span>
            <input type='number' name='Aantal-7' id='Aantal-7' value=0></select>
          </div>

          <br>
          <div class="form-item">
            <span>SubProduct</span>
            <select name='SubID-8' id='SubID-8'>
            <?php
              foreach($voorraad as $product){
                echo "<option value='".$product['ID']."'>".$product['ID'].": ".$product["Naam"]."</option>";
              }
            ?></select>
          </div>
          <div class="form-item">
            <span>Aantal</span>
            <input type='number' name='Aantal-8' id='Aantal-8' value=0></select>
          </div>


          <br>
          <div class="form-item">
            <span>SubProduct</span>
            <select name='SubID-9' id='SubID-9'>
            <?php
              foreach($voorraad as $product){
                echo "<option value='".$product['ID']."'>".$product['ID'].": ".$product["Naam"]."</option>";
              }
            ?></select>
          </div>
          <div class="form-item">
            <span>Aantal</span>
            <input type='number' name='Aantal-9' id='Aantal-9' value=0></select>
          </div>

          <br>
          <div class="form-item">
            <span>SubProduct</span>
            <select name='SubID-10' id='SubID-10'>
            <?php
              foreach($voorraad as $product){
                echo "<option value='".$product['ID']."'>".$product['ID'].": ".$product["Naam"]."</option>";
              }
            ?></select>
          </div>
          <div class="form-item">
            <span>Aantal</span>
            <input type='number' name='Aantal-10' id='Aantal-10' value=0></select>
          </div>



      </div>
      <div class="form-item">
          <span><br></span>
       <input type='submit' name='voorraad-submit' value='Toevoegen'>
     </div>
      </form>
      <form class='hiddenform' method='post'>
        <input type='text' name='action' id='action'>
        <input type='text' name='id' id='id'>
        <input type='text' name='fromid' id='fromid'>
        <input type='text' name='subid' id='subid'>
        <input type='submit' name='action-submit' id='action-submit'>
      </form>

    </div>
    <div id='edit-product'>
      <i class="material-icons" id='productBack' onclick='editTerug()'>arrow_back</i>
    <form class="editform bestelling-form" id='editform' style="display: none;" method='post'>
      <div class="form-item" style="display:none;">
        <span>Product</span>
      <input name='ID' id='ID-old' type="text">
      <br>
      </div>
      <div class="form-item">
        <span>Product</span>
      <select name='FromID' id='ID-edit'>
      <?php
        foreach($voorraad as $product){
          echo "<option value='".$product['ID']."'>".$product['ID'].": ".$product["Naam"]."</option>";
        }
      ?></select>
      </div>
      <div class="form-item">
        <span>SubProduct</span>
      <select name='SubID' id='SubID-edit'>
        <?php
          foreach($voorraad as $product){
            echo "<option value='".$product['ID']."'>".$product['ID'].": ".$product["Naam"]."</option>";
          }
        ?></select>
      </div>
      <div class="form-item">
        <span>Aantal</span>
      <input type='number' name='Aantal' id='Aantal-edit'></select>
      </div>
      <input type='submit' name='edit-submit'>
    </form>

    </div>
    </div>
      </div>

  </main>











</div>




</body>



</html>
