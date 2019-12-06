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
   $host        = "host = ec2-174-129-43-40.compute-1.amazonaws.com";
   $port        = "port = 5432";
   $dbname      = "dbname = d401qebkdh7ra";
   $credentials = "user = iuqodffpzenrye password=7c50613b97c4cf89e3ae97c49cb6b52db20379b7239705ad78ec98ee02210152";

   $conn = pg_connect( "$host $port $dbname $credentials"  );
   $db = false;
   if(!$conn) {
      echo "Error : Unable to open database\n";
      return false;
   } else {
    global $db;
  
   }

?>