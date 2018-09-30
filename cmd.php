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
echo "\n";
$phell = new Phell();
while ($phell->isActive()) {
    $phell->cli();
}

//Commande fin d'execution
/*
 * 
 */