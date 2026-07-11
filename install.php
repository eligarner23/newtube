<?php
// -----------------------------------------
// NewTube Auto Installer (No GUI Required)
// -----------------------------------------

$host = "localhost";
$user = "root";   // default for most local setups
$pass = "";       // empty unless you set a password

// Connect to MySQL server (NOT a database yet)
$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS newtube";
if ($conn->query($sql) === TRUE) {
    echo "Database 'newtube' created or already exists.<br>";
} else {
    die("Error creating database: " . $conn->error);
}

// Select database
$conn->select_db("newtube");

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === TRUE) {
    echo "Table 'users' created.<br>";
} else {
    die("Error creating users table: " . $conn->error);
}

// Create videos table
$sql = "CREATE TABLE IF NOT EXISTS videos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    filename VARCHAR(255) NOT NULL,
    thumbnail VARCHAR(255) NOT NULL,
    uploader VARCHAR(50) NOT NULL,
    views INT DEFAULT 0,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === TRUE) {
    echo "Table 'videos' created.<br>";
} else {
    die("Error creating videos table: " . $conn->error);
}

echo "<br><strong>NewTube installation complete!</strong><br>";
echo "You can now delete install.php.";
?>
