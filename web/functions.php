<?php

//Securite
defined("PHELL") OR exit('Direct access not allowed');

/* === Fonctions === */

function newLine($str){
    return str_replace("\n", "<br>", $str);
}