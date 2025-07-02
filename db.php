<?php
$servername = "tcp:eventserver123.database.windows.net,1433";
$username = "sqladmin"; // your admin username
$password = "YourStrongPassword!"; // your actual Azure SQL password
$dbname = "event_db"; // your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
