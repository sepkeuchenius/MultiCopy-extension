<?php


function logMessage($ID , $bericht)
{
  $mysqli = new mysqli("rdbms.strato.de","U4442376","Wandeling2020!","DB4442376");

  // Check connection
  if ($mysqli -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
    exit();
  }

  $sql = "INSERT INTO `LOG` (`ID`, `Tijd`, `Bericht`) VALUES ('$ID',NOW(),'$bericht')";
  $result = $mysqli->query($sql);
  if($result){
    return TRUE;
  }
  else{
    echo $mysqli->error;
  }

}

 ?>
