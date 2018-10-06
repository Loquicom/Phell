<?php

//Securite
define("PHELL", 1, true);

//Chargement fichier requis (Custom)
/*
  require 'framework.php';
 */

//Chargement Phell (PHP Shell)
require 'phell.php';

//Configuration Phell
$config = json_decode(file_get_contents("config.json"), JSON_OBJECT_AS_ARRAY);
foreach ($config['dir'] as $dir){
    Phell::addDir($dir);
}
Phell::setRecursiveScan($config['recursive']);

//Lance la version web
require 'web/functions.php';
require 'web/phell.php';

//Commande fin d'execution
/*
 * 
 */