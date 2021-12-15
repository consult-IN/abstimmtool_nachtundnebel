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

            $stmt = $mysql->prepare("SELECT * FROM stimmen_weitergaben WHERE ZU = :email"); 
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            $count = $stmt->rowCount();
            $stimmen = $count + 1;

            $selected_votes = 0;

            for($i = 0; $i<count($selected); $i = $i + 2){
                $selected_votes = $selected_votes + intval($selected[$i+1]);
            }

            if($stimmen > $selected_votes){
                print("<script>alert('Error: Du wolltest mehr Stimmen abgeben, als du hast!')</script>");
                $result = 3;
            }else{
                
                if($selected_votes<$stimmen){

                    $selected = "EXEEROR";
                    $insert_vote = 0;
                    $diff = ($stimmen - $selected_votes);

                    for($i = 1; $i<=$diff; $i++){
                        $stmt = $mysql->prepare("INSERT INTO voting_results (WAHL_ID, ITEM, VOTES) VALUES (:wahl_id, :selected, :stimmen)"); 
                        $stmt->bindParam(":selected", $selected);
                        $stmt->bindParam(":wahl_id", $wahl_id);
                        $stmt->bindParam(":stimmen", $insert_vote);
                        $stmt->execute();
                    }

                    $stmt = $mysql->prepare("INSERT INTO users_voted (VON, WAHL_ID) VALUES (:email, :wahl_id)");
                    $stmt->bindParam(":email", $email);
                    $stmt->bindParam(":wahl_id", $wahl_id);
                    $stmt->execute();
                    $result = 0;

                }else{

                    $stmt = $mysql->prepare("SELECT * FROM stimmen_weitergaben WHERE VON = :email"); 
                    $stmt->bindParam(":email", $email);
                    $stmt->execute();
                    $stmt->execute();
                    $count = $stmt->rowCount();
                    if ($count == 1) {
                        $stimmen = -1;
                        print("<script>alert('Achtung! Du hast deine Stimme an eine andere Person weitergegeben! Deine Stimme zählt nicht.')</script>");
                    }

                    if($stimmen < 0){
                        print("<script>alert('Error: Negative Stimmenzahl')</script>");
                        $result = 2;
                    }else{
                    //iterate through array.. 0er auslassen
                    for($i = 0; $i<=count($selected); $i = $i + 2){
                        $stmt = $mysql->prepare("INSERT INTO voting_results (WAHL_ID, ITEM, VOTES) VALUES (:wahl_id, :selected, :stimmen)"); 
                        $stmt->bindParam(":selected", $selected[$i]);
                        $stmt->bindParam(":wahl_id", $wahl_id);
                        $votes_to_insert = $stimmen[$i+2];
                        $stmt->bindParam(":stimmen", $votes_to_insert);
                        $stmt->execute();
                    }

                    $stmt = $mysql->prepare("INSERT INTO users_voted (VON, WAHL_ID) VALUES (:email, :wahl_id)");
                    $stmt->bindParam(":email", $email);
                    $stmt->bindParam(":wahl_id", $wahl_id);
                    $stmt->execute();
                    $result = 0;
                    }
                }
            
            }

        }
    
    return $result;
}
