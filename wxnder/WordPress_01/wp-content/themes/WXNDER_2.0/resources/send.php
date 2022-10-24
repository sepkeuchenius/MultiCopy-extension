<?php
try{
$mysqli = new mysqli("rdbms.strato.de","U4442376","Wandeling2020!","DB4442376");
if($mysqli->connect_error) {
  exit('Could not connect');
}
$user = $_POST["User"];
$page = $_POST["Page"];
$event1 = $_POST["Event1"];
$event2 = $_POST["Event2"];
$event3 = $_POST["Event3"];
$query = $_POST["Query"];
$sql = "INSERT INTO `WebsiteAnalytics` (User, Page, Event1, Event2, Event3, Query) VALUES ($user, '$page', '$event1', '$event2', '$event3', '$query')";
$resultVoorraad = $mysqli->query($sql);
exit($resultVoorraad);
}
catch(Exception $e){
    exit("Fout: " . htmlspecialchars($e->getMessage()));
}
?>
