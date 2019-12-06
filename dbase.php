<?php

// $host = 'localhost';
// $dbname = 'golball';
// $user = 'root';
// $pass = '';
// try {
//     $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
//     $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
//     global $conn;
// } catch (PDOException $e) {
//     print "Error!: " . $e->getMessage() . "<br/>";
//     die();
// }
   $host        = "host = ec2-54-217-234-157.eu-west-1.compute.amazonaws.com";
   $port        = "port = 5432";
   $dbname      = "dbname = dav5nd421ipu74";
   $credentials = "user = lfpegwcrzqkfgp password=52bc85c5bec01bb9563d98e20dcad713319986829d6321939892fe4f3787227c";

   $conn = pg_connect( "$host $port $dbname $credentials"  );
   $db = false;
   if(!$db) {
      echo "Error : Unable to open database\n";
   } else {
    global $db;
  
   }

?>