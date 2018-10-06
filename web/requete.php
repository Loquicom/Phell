<?php

define("PHELL", 1, true);

//Charge fichier de fonction
require 'functions.php';

//Verification de la presence du post
if (!testInput()) {
    result(false);
}

//Recup la class phell
require '../phell.php';

//Recup session
session_name('WebPhell');
session_start();
if (!isset($_SESSION['phell'])) {
    result(false);
}
$phell = $_SESSION['phell'];

//Recharge les class
$config = json_decode(file_get_contents("../config.json"), JSON_OBJECT_AS_ARRAY);
foreach ($config['dir'] as $dir) {
    Phell::addDir($dir);
}
$phell->reloadClass('../');

ob_start();
//Execute la commande
$argv = explode(" ", $_POST['input']);
$res = $phell->exec($argv);
$output = ob_get_contents();
//Retour
ob_end_clean();
if (is_bool($res)) {
    //Si boolean
    if ($res) {
        result(true, adapt($output));
    } else {
        result(false, adapt($output));
    }
}
//Si JSON
else {
    $json = json_decode($res, JSON_OBJECT_AS_ARRAY);
    //Verif json
    if ($json == null) {
        result(false);
    }
    //Retour
    if (isset($json['link'])) {
        if (isset($json['show'])) {
            result_link($json['link'], adapt($output), $json['show']);
        } else {
            result_link($json['link'], adapt($output));
        }
    } else {
        if (isset($json['show'])) {
            result(true, adapt($output), $json['show']);
        } else {
            result(true, adapt($output));
        }
    }
}



