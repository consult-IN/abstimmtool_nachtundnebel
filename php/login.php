<?php

require("sec/mysql.php");
require("sendverification_email.php");
require("check_email.php");

if (isset($_POST["login"])) {


    $email = $_POST["email"];
    $pwd = $_POST["pwd"];

    $is_consultin_email = checkEmail($email);

    if ($is_consultin_email) {

        //check if account data is already in database and validated

        $stmt = $mysql->prepare("SELECT * FROM users WHERE EMAIL = :email"); //email überprüfen
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $count = $stmt->rowCount();
        if ($count == 1) {
            $stmt = $mysql->prepare("SELECT * FROM users WHERE EMAIL = :email AND VERIFIED = :verified"); //verified überprüfen
            $stmt->bindParam(":email", $email);
            $verify_nr = 1;
            $stmt->bindParam(":verified", $verify_nr);
            $stmt->execute();
            $count = $stmt->rowCount();
            if ($count == 1) {
                $row = $stmt->fetch();
                if (password_verify($pwd, $row["PASSWORD"])) {
                    session_start();
                    $_SESSION["email"] = $row["EMAIL"];
                    $_SESSION["level"] = $row["LEVEL"];
                    header("Location: voting.php");
                } else {
                    print("<script>alert('Falsches Passwort!')</script>");
                }
            } else {
                //sendEmail($email);
                print("<script>alert('Account wurde noch nicht validiert. Bitte überprüfe deine Emails und bestätige über den enthaltenen Link.')</script>");
                //email has been send to verfy account, please check your email
            }
        } else {
            //sendEmail($email);
            //email has been send to verfy account, please check your email 
            print("<script>alert('Bitte überprüfe deine Emails und bestätige über den enthaltenen Link.')</script>");
            $stmt = $mysql->prepare("INSERT INTO users (EMAIL, VERIFIED, LEVEL, PASSWORD) VALUES (:email, :verified, :level, :pw)");

            $stmt->bindParam(":email", $_POST["email"]);
            $level_nr = 0;
            $stmt->bindParam(":level", $level_nr);
            $verify_nr = 0;
            $stmt->bindParam(":verified", $verify_nr);
            $hash = password_hash($_POST["pwd"], PASSWORD_BCRYPT);
            $stmt->bindParam(":pw", $hash);
            $stmt->execute();
        }
    } else {
        print("<script>alert('Bitte nutze deine @consultin.net Email-Adresse!')</script>");
    }
}
