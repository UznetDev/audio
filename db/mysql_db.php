<?php
$servername = "localhost";
$username = "youtube";
$password = "youtube";
$dbname = "youtube";

// $servername = "localhost";
// $username = "project";
// $password = "myproject";
// $dbname = "youtube";

try{
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    $conn->set_charset('utf8mb4');
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

}
catch(Exception $err){
    returner(/* okk */False,/* $result */ Null, /* meta */ Null, /* message */ $err, /* code */ 0,  /* error */ true);
}