<?php

if (isset($_POST["code"])) {
    require("sec/mysql.php");
    $stmt = $mysql->prepare("SELECT * FROM verification WHERE CODE = :code AND EMAIL = :email"); //code überprüfen
    $stmt->bindParam(":code", $_POST["code"]);
    $stmt->bindParam(":email", $_POST["email"]);
    $stmt->execute();
    $count = $stmt->rowCount();
    if ($count == 1) {
        //set user to validated
        $stmt = $mysql->prepare("UPDATE `users` SET `VERIFIED` = '1' WHERE `users`.`EMAIL` = :email;");
        $stmt->bindParam(":email", $_POST["email"]);
        $stmt->execute();
        //delete code from code database
        $stmt = $mysql->prepare("DELETE FROM `verification` WHERE `verification`.`CODE` = :code");
        $stmt->bindParam(":code", $_POST["code"]);
        $stmt->execute();
        echo "Success";
    }

    echo "Error";
}
