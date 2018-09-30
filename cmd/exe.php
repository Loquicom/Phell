<?php
///Execute des commandes systemes

//Retire le nom de la commande phell
unset($argv[0]);
//Execute la commande
$output = [];
exec(implode(" ", $argv), $output);
//Affiche le resultat
if (!empty($output)) {
    echo "\n" . implode("\n", $output);
}
return Phell::SUCCESS;