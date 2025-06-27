<?php
$servername = "localhost";
$username = "root";
$password = ""; // Default for XAMPP
$dbname = "event_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
