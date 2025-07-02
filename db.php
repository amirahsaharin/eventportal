<?php
$servername = "tcp:yourservername.database.windows.net,1433";  // Azure SQL server
$username = "sqladmin";        // Your admin username
$password = "YourStrongPassword!";  // The password you set in Azure
$dbname = "event_db";          // Your database name in Azure

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
