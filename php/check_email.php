<?php

function checkEmail($email)
{
    if (preg_match("/@consultin.net$/", $email)) {
        return True;
    } else {
        return False;
    }
}
