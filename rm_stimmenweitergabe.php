<?php

session_start();
if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit;
}

require("php/sec/mysql.php");

$stmt = $mysql->prepare("SELECT * FROM users_voted WHERE von = '" . $_SESSION["email"] . "'");
$stmt->execute();
$count = $stmt->rowCount();
if ($count == 1) {
    echo "<script>alert('Fehler: Der User, an den du deine Stimme weitergegeben hast, hat bereits abgestimmt. Du kannst deine Stimme nun nicht mehr zurückfordern!.')</script>";
} else {

    $stmt = $mysql->prepare("DELETE FROM `stimmen_weitergaben` WHERE `stimmen_weitergaben`.`VON` = '" . $_SESSION["email"] . "';");

    $stmt->execute();
}

header('Location:stimme_weitergeben.php');
