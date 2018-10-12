<?php
///Execute des commandes systemes

//Verif qu'il y a bien un commande a executer
if($argc <= 1){
    $return = Phell::FAIL;
}
//Retire le nom de la commande phell
unset($argv[0]);
//Execute la commande
$output = [];
exec(implode(" ", $argv), $output);
//Affiche le resultat
if (!empty($output)) {
    echo "\n" . implode("\n", $output) . "\n";
}
$return = Phell::SUCCESS;