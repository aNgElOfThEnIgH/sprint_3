<?php
$servername = "localhost";
$username = "root";
$password = "Senai@118";
$dbname = "nexdrone";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
