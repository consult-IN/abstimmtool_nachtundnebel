<?php

session_start();
if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit;
}

require("php/sec/mysql.php");

$stmt = $mysql->prepare("DELETE FROM `stimmen_weitergaben` WHERE `stimmen_weitergaben`.`VON` = '" . $_SESSION["email"] . "';");

$stmt->execute();

header('Location:stimme_weitergeben.php');