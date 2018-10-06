<?php

//Securite
defined("PHELL") OR exit('Direct access not allowed');

/* === Fonctions === */

function newLine($str){
    return str_replace("\n", "<br>", $str);
}

function testInput(){
    return isset($_POST['input']) && trim($_POST['input']) != '';
}

function result(bool $etat, $msg = null){
    $res = ['etat' => $etat];
    if($msg != null){
        $res['msg'] = $msg;
    }
    echo json_encode($res);
    exit;
}