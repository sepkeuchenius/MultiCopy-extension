<?php
  if(!isset($_GET['id'])){
    echo "Bestelling niet gevonden";
    $display = 'none';
  }
  else{
    $id = $_GET['id'];
    $mysqli = new mysqli("rdbms.strato.de","U4442376","Wandeling2020!","DB4442376");

    // Check connection
    if ($mysqli -> connect_errno) {
      echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
      exit();
    }

    $query = "SELECT * FROM Bestellingen WHERE ID=$id";
    $result = $mysqli->query($query);
    if($result->num_rows > 0){
      while($row = $result->fetch_assoc()){
        $besteld = $row['Aangemaakt'];
        $betaald = $row['Betaald'];
        $leverdatum = $row['Leverdatum'];
        $gemaakt = $row['Geproduceerd'];
        $geleverd = $row['Geleverd'];
      }
    }
    else{
      echo "Bestelling niet gevonden";
      $display = 'none';
    }



  }
  $empty = '0000-00-00 00:00:00';
  $status = 'Niet besteld';
  if($besteld != $empty AND  $betaald != $empty){
    $status= 'Betaald';
  }
  if($leverdatum != $empty AND strlen($leverDatum) >0){
    $status = 'Gepland';
  }
  if($gemaakt != $empty AND strlen($leverdatum)>0){
    $status = 'Gemaakt';
  }
  if($geleverd != $empty AND strlen($geleverd)>0){
    $status = 'Geleverd';
  }
  echo $status;

 ?>
