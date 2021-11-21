<?php

session_start();
if (!isset($_SESSION["email"]) && $_SESSION["level"] >= 1) {
    header("Location: index.php");
    exit;
}

require("php/sec/mysql.php");

$id = $_GET['wahlid'];

$stmt = $mysql->prepare("SELECT * FROM wahlen WHERE ACTIVE = :active"); 
$active = 1;
$stmt->bindParam(":active", $active);
$stmt->execute();
$count = $stmt->rowCount();
if ($count == 0) {

        $stmt = $mysql->prepare("UPDATE `wahlen` SET `ACTIVE` = '1' WHERE `wahlen`.`WAHL_ID` = " . $id . ";");
        $stmt->execute();
        header('Location:wahlleiter.php');
}else{
        echo "<script>alert('Fehler es kann nur eine aktive Wahl geben. Schließe zuerst die aktive Wahl, bevor du eine neue aktivierst.')</script>";
        
}