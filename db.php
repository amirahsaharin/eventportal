<?php
$servername = "eventmysql123.mysql.database.azure.com";
$username = "sqladmin@eventmysql123";  // Your full user name
$password = "Admin123";     // Your actual password
$dbname = "event_db";

// ✅ Enable SSL
$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

if (!$conn->real_connect($servername, $username, $password, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL)) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
