<?php
$servername = "tcp:<your-server-name>.database.windows.net,1433";
$username = "sqladmin";
$password = "YourStrongPassword!";
$dbname = "event_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
