<?php

function onlyValidCharacters($name)
{
    if (preg_match("/^[a-zA-ZäöåÄÖÅ\s]+$/", $name)) {
        //echo $name;
        return true;
    } else {
        return false;
    }
}

function validLength($name)
{

    if (mb_strlen($name, 'utf8') >= 2 && mb_strlen($name, 'utf8') <= 30) {
        return true;
    } else {
        return false;
    }

}