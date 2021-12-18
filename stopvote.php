<?php
session_start();
if (!isset($_SESSION["email"]) && $_SESSION["level"] >= 1) {
    header("Location: index.php");
    exit;
}

require("php/sec/mysql.php");

$id = $_GET['wahlid'];

        $stmt = $mysql->prepare("SELECT * FROM wahlen WHERE WAHL_ID = :wahl_id"); 
        $stmt->bindParam(":wahl_id", $id);
        $stmt->execute();
        $count = $stmt->rowCount();
        if ($count = 1) {

            $row = $stmt->fetch();
            $elements = explode(',', $row["ITEMS"]);
            array_push($elements, "Enthaltungen");

            for ($i = 0; $i < count($elements); $i++) {
                $votes = 0;
                $db_erg_entry = mysqli_query($db_link, 'SELECT * FROM voting_results WHERE ITEM = "' . $elements[$i] . '" AND WAHL_ID = ' . $id . ';');

                if (!$db_erg_entry) {
                    $stmt = $mysql->prepare("INSERT INTO voting_results_total (WAHL_ID, ITEM, VOTES_TOTAL) VALUES (:wahl_id, :item, :votes)"); 
                    $stmt->bindParam(":wahl_id", $id);
                    $stmt->bindParam(":item", $elements[$i]);
                    $stmt->bindParam(":votes", $votes);
                    $stmt->execute();
                    continue;
                }

                while ($zeile = mysqli_fetch_array($db_erg_entry, MYSQLI_ASSOC)) {
                    $votes = $votes + $zeile['VOTES'];
                }

                $stmt = $mysql->prepare("INSERT INTO voting_results_total (WAHL_ID, ITEM, VOTES_TOTAL) VALUES (:wahl_id, :item, :votes)"); 
                $stmt->bindParam(":wahl_id", $id);
                $stmt->bindParam(":item", $elements[$i]);
                $stmt->bindParam(":votes", $votes);
                $stmt->execute();

            }
        }
        

        $stmt = $mysql->prepare("DELETE FROM `wahlen` WHERE `wahlen`.`WAHL_ID` = " . $id);
        $stmt->execute();
        header("Location:wahlleiter.php");

?>