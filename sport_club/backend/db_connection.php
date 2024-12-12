<?php
$servername = "localhost:3307";
$username = "root";
$password = "L!nhtetz@w492OO2";
$dbname = "pmpsdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

