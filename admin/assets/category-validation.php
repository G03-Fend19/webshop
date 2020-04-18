<?php

function onlyValidCharacters($name)
{
    if (preg_match("/^[a-zA-ZäöåÄÖÅ\s]+$/", $name)) {
        echo $name;
        return true;
    } else {
        return false;
    }
}

function validLength($name)
{
    if (strlen($name) >= 2 && strlen($name) <= 30) {
        return true;
    } else {
        return false;
    }

}
