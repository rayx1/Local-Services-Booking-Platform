<?php
// Update these values to match your local MySQL setup.
$dbHost = 'localhost';
$dbName = 'local_services_booking_platform';
$dbUser = 'root';
$dbPass = '';

$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($mysqli->connect_error) {
    die('Database connection failed: ' . $mysqli->connect_error);
}

$mysqli->set_charset('utf8mb4');
?>
