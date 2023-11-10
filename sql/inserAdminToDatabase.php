<?php


$host = "localhost";
$user = "root";
$password = "";
$database = "CDHarmonyDB";

// Connect to the database
$db = new mysqli($host, $user, $password, $database);

// Check the connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}


$setPass = $_GET['setPass'];
$iterations = ['cost' => 15];
$Hpass = password_hash($setPass, PASSWORD_BCRYPT, $iterations);

//Hpass is then saved to DB
$query = "INSERT INTO hash (pass) VALUES ('{$Hpass}')";
$result = mysqli_query($connection, $query);
echo "This is the hashed pass: ".$Hpass . "<br>";
echo "The length of the string is: ". strlen($Hpass);