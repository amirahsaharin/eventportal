<?php
$servername = "eventmysql123.mysql.database.azure.com";
$username = "mysqladmin@eventmysql123";  // Your full user name
$password = "MyEventDB123!";     // Your actual password
$dbname = "event_db";

$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

if (!$conn->real_connect($servername, $username, $password, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL)) {
    die("❌ Connection failed: " . mysqli_connect_error());
} else {
    echo "✅ Connected successfully to Azure MySQL!";
}
?>
