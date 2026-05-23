<?php

$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "evoting";
$port = 3307;

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("DB Connection Failed: " . $conn->connect_error);
}

?>