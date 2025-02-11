<?php
$host = "localhost";
$user = "root";
$password = "1234";
$database = "user_db";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection Failed:  ". $conn->connect_error);

}


?>