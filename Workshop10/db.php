<?php
$server = "localhost";
$username = "root";
$password = "";
$dbname = "workshop10";

$conn = new mysqli($server, $username, $password, $dbname);
if ($conn->connect_error){
    die("connection failed:" . $conn->connect_error);
}
?>