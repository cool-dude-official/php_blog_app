<?php
$servername = "127.0.0.1";  // <-- use IP instead of localhost
$username = "root";
$password = "";  // empty password for XAMPP
$database = "blog_app";
$port = 3306;

// Optional: specify socket for macOS XAMPP
$socket = '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock';

// Create connection
$conn = new mysqli($servername, $username, $password, $database, $port, $socket);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
?>
