<?php

//Securite
defined("PHELL") OR exit('Direct access not allowed');

/* === Fonctions === */

function adapt($str) {
    $str = str_replace(["\n", "\t", " "], ["<br>", "&Tab;", "&nbsp;"], $str);
    if(substr($str, strlen($str) - 4) === '<br>'){
        $str = substr($str, 0, strlen($str) - 4);
    }
    return $str;
}

function testInput() {
    return isset($_POST['input']) && trim($_POST['input']) != '';
}

function result(bool $etat, $msg = '', bool $show = null) {
    $res = [
        'etat' => $etat,
        'msg' => $msg
    ];
    if($show !== null){
        $res['pass'] = !$show;
    }
    echo json_encode($res);
    exit;
}

function result_link($link, $msg = '', bool $show = null) {
    $res = [
        'etat' => true,
        'msg' => $msg,
        'link' => $link
    ];
    if($show !== null){
        $res['pass'] = !$show;
    }
    echo json_encode($res);
    exit;
}