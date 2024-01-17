
<?php

$dbHost = "localhost";
$dbPort = "5432";
$dbName = "iLearnX";
$dbUser = "postgres";
$dbPassword = "pgAdmin4";

$conn = pg_connect("host=$dbHost port=$dbPort dbname=$dbName user=$dbUser password=$dbPassword");

if (!$conn) {
    die("Failed to connect to the database.");
}

$checkQuery = "SELECT * FROM courses";
$checkResult = pg_query($conn, $checkQuery);

if ($checkResult) {
    $courses = array();

    while ($row = pg_fetch_assoc($checkResult)) {
        $courses[] = $row;
    }
} else {
    echo "Query execution failed: " . pg_last_error($conn);
}

pg_close($conn);

?>

