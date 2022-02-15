<?php

require("sec/mysql.php");

function refresh_active_memebers($value)
{

    require("sec/mysql.php");

    $stmt = $mysql->prepare("UPDATE `active_member` SET `active_mem` = '" . $value . "' WHERE `active_member`.`ACTIVE_MEM_ID` = 1;");
    $stmt->execute();
    header('Location:wahlleiter.php');
}
