<?php
$dbHost = "localhost";
$dbPort = "5432";
$dbName = "iLearnX";
$dbUser = "postgres";
$dbPassword = "pgAdmin4";

$conn = pg_connect("host=$dbHost port=$dbPort dbname=$dbName user=$dbUser password=$dbPassword");
if (!$conn) {
    die("Connection failed: " . pg_last_error());
}
?>
