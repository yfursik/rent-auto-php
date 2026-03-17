<?php
$host = 'localhost';
$dbname = 'rent_auto';
$username = 'root';
$password = '';

// Připojení k DB
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error: " . $conn->connect_error);
}

// Nastavení kódování
$conn->set_charset("utf8mb4");
?>