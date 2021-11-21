<?php

function sendEmail($email)
{
    require("sec/mysql.php");

    $code_generating = True;

    $code = "";

    do {
        $code = generateCode();

        $stmt = $mysql->prepare("SELECT * FROM verification WHERE CODE = :code"); //code überprüfen
        $stmt->bindParam(":code", $code);
        $stmt->execute();
        $count = $stmt->rowCount();
        if ($count >= 1) {
            $code_generating = False;
        }
    } while ($code_generating == True);

    $stmt = $mysql->prepare("INSERT INTO verification (CODE, EMAIL) VALUES (:code, :email)");
    $stmt->bindParam(":code", $code);
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    $nachricht = "Bitte klicke diesen Link:\r\n+Link+";
    $nachricht = wordwrap($nachricht, 70, "\r\n");
    mail('$email', 'Dein Abstimmtool verification Link', $nachricht);
}



function generateCode()
{
    $length = 40;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
}
