<?php
// Database credentials
$server = "localhost";
$username = "root";
$password = "";
$database = "ekeble";

// Create connection
$database = new mysqli($server, $username, $password, $database);

// Check connection
if ($database->connect_error) {
    die("Connection failed: " . $database->connect_error);
}

// Set character set to utf8
$database->set_charset("utf8");
?>