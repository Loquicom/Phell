<?php

//Securite
if (!isset($_SERVER['argv'])) {
    exit('Only CLI');
}
define("PHELL", 1, true);

//Chargement fichier requis (Custom)
/*
  require 'framework.php';
 */

//Chargement Phell (PHP Shell)
require 'phell.php';

//Configuration Phell
$config = json_decode(file_get_contents("config.json"), JSON_OBJECT_AS_ARRAY);
foreach ($config['dir'] as $dir) {
    Phell::addDir($dir);
}
Phell::setRecursiveScan($config['recursive']);


//Lancement Phell
if ($argc > 1) {
    //Execution commande unique
    array_shift($argv);
    $phell = new Phell($argv);
} else {
    //Instanciation Phell
    $phell = new Phell();
    //Configuration instance Phell
    if (trim($config['prompt']) != '') {
        $phell->setPrompt($config['prompt']);
    }
    //Execution en boucle
    while ($phell->isActive()) {
        $res = $phell->cli();
    }
}

//Commande fin d'execution
/*
 * 
 */
