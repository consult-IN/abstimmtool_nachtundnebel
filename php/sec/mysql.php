<?php
// Database-Conection param.
$host = "localhost";
$name = "abstimmtool";
$user = "root";
$passwort = "";

try {
    $mysql = new PDO("mysql:host=$host;dbname=$name", $user, $passwort);
    $db_link = mysqli_connect(
        $host,
        $user,
        $passwort,
        $name
    );
} catch (PDOException $e) {
    echo "SQL Error: " . $e->getMessage();
}
