<?php

function vote($selected, $email, $wahl_id){

    require("sec/mysql.php");

    $stmt = $mysql->prepare("SELECT * FROM users_voted WHERE VON = :email AND WAHL_ID = :wahl_id"); 
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":wahl_id", $wahl_id);
        $stmt->execute();
        $count = $stmt->rowCount();
        if ($count >= 1) {
            echo "<script>alert('Fehler: Du hast bereits abgestimmt.')</script>";
            $result = 1;
        }else{

            $stmt = $mysql->prepare("INSERT INTO users_voted (VON, WAHL_ID) VALUES (:email, :wahl_id)"); 
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":wahl_id", $wahl_id);
            $stmt->execute();  
            

            $stmt = $mysql->prepare("SELECT * FROM stimmen_weitergaben WHERE ZU = :email"); 
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            $count = $stmt->rowCount();
            $stimmen = $count + 1;

            $stmt = $mysql->prepare("INSERT INTO voting_results (WAHL_ID, ITEM, VOTES) VALUES (:wahl_id, :selected, :stimmen)"); 
            $stmt->bindParam(":selected", $selected);
            $stmt->bindParam(":wahl_id", $wahl_id);
            $stmt->bindParam(":stimmen", $stimmen);
            $stmt->execute();


            $result = 0;

        }
    
    return $result;
}

?>