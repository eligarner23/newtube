<?php
$conn = new mysqli("localhost", "root", "newtube123", "newtube");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected to MariaDB successfully!<br>";

// Test query
$result = $conn->query("SHOW TABLES");

echo "Tables in database:<br>";
while ($row = $result->fetch_array()) {
    echo "- " . $row[0] . "<br>";
}
?>
