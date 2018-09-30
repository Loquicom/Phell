<?php

//Chargement fichier requis (Custom)
/*
  require 'framework.php';
 */

//Chargement Phell (PHP Shell)
require 'phell.php';

//Configuration Phell
/*
 * 
 */

//Lancement Phell
$phell = new Phell();
if ($argc > 1) {
    //Execution commande unique
    $argc--;
    array_shift($argv);
    $phell->launch($argc, $argv);
} else {
    //Execution en boucle
    while ($phell->isActive()) {
        $phell->cli();
    }
}

//Commande fin d'execution
/*
 * 
 */