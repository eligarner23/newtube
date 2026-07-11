<?php
$host = "localhost";
$user = "newtube";        // your new MariaDB user
$pass = "newtube123";     // your MariaDB password
$db   = "newtube";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
