<?php

//Recup la class phell
define("PHELL", 1, true);
require '../phell.php';

//Recup session
session_name('WebPhell');
session_start();

//Liste les commandes disponible dans le phell
if(isset($_SESSION['phell'])){
    $cmd = $_SESSION['phell']->getCommands();
    //Envoi
    echo json_encode($cmd);
}