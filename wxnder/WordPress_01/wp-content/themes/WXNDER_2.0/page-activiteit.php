<?php
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
$queryActiviteit = "SELECT * FROM `WebsiteAnalytics`";

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
$resultActiviteit = $mysqli->query($queryActiviteit);




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
  Activiteit WXNDER
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
<script src='https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js'></script>
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
    <a href="/productactiviteit">PRODUCTACTIVITEIT</a>
    <a href="/productactiviteit" style="border-bottom:2px solid orange;">ACTIVITEIT</a>


  </header>
  <main class="mdl-layout__content">

      <div class="page-content">
        <div id="content">
          <div id='voorraad'>
        <table class="mdl-data-table mdl-js-data-table  mdl-shadow--2dp" id='voorraadtabel'>
        <thead>
          <tr>
            <th>User</th>
            <th>Tijd</th>
            <th>Page</th>
            <th>Query</th>
            <th>1</th>
            <th>2</th>
            <th>3</th>

          </tr>
        </thead>
        <tbody>
          <?php


          if($resultActiviteit->num_rows > 0){
            while($row = $resultActiviteit->fetch_assoc()){


              $User = "<td class='td_ID'>".$row["User"]."</td>";
              $Tijd = "<td class='td_Naam'>".$row['Time']."</td>";
              $Page = "<td class='td_Naam'>".$row['Page']."</td>";
              $Query = "<td class='td_Naam'>".substr($row['Query'],0,20)."</td>";
              $Event1 = "<td class='td_Naam'>".$row['Event1']."</td>";
              $Event2 = "<td class='td_Naam'>".$row['Event2']."</td>";
              $Event3 = "<td class='td_Naam'>".$row['Event3']."</td>";


              $text = $User.$Tijd.$Page.$Query.$Event1.$Event2.$Event3;
              echo "<tr>".$text."</tr>";
            }
          }
             ?>



        </tbody>
      </table>
    </div>


    </div>
    <div id='data-results'>
      <div class='data-result'>
        <p style="width:100%; text-align:center;font-size:20px;padding:10px;color:#4d604e">Laatste activiteit: <span id="last-activity"></span> minuten geleden.</p>
      </div>

    </div>

      </div>

  </main>











</div>



<script src="<?php echo get_template_directory_uri();?>/js/WebsiteAnalytics.js"></script>
</body>



</html>
