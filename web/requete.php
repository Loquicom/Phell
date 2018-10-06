<?php

define("PHELL", 1, true);

//Charge fichier de fonction
require 'functions.php';

//Verification de la presence du post
if(!testInput()){
    result(false);
}

//Recup la class phell
require '../phell.php';

//Recup session
session_name('WebPhell');
session_start();
if(!isset($_SESSION['phell'])){
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
ob_end_clean();
if($res){
    result(true, adapt($output));
} else {
    result(false, adapt($output));
}

