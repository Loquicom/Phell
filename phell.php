<?php

/**
 * Description of Phell
 *
 * @author Loquicom <contact@loquicom.fr>
 */
class Phell {
    
    /**
     * Constante de retour
     */
    const SUCCESS = 1;
    const FAIL = 0;
    const ERROR = -1;
    
    /**
     * Liste des repertoires avec les fichiers des commandes
     * @var string[]
     */
    protected static $dir = ['cmd'];
    
    /**
     * Liste des commandes dispo
     * @var string[string]
     */
    protected $cmd = ['help' => 'Affiche les commandes disponible', 'exe' => 'Execute des commandes systemes', 'quit' => 'Ferme le phell'];
    
    /**
     * Liste des class deja chargée et leur instance
     * @var array[]
     */
    protected $class = [];
    
    /**
     * Liste des method pour une commande dans une class
     * @var type 
     */
    protected $cmdClass = ["help" => ['obj' => 'phell', 'method' => 'help'], "exe" => ['obj' => 'phell', 'method' => 'exe'], 'quit' => ['obj' => 'phell', 'method' => 'quit']];

    /**
     * Indique si le phell est actif
     * @var boolean
     */
    protected $active = true;

    /* === Constructeur === */
    
    public function __construct() {
        //Ajoute le phell dans les class avec des commandes
        $this->class['phell'] = $this;
    }
    
    /* === Commande gestion === */
    
    public function cli(){
        //Ecriture
        echo "phell> ";
        //Attente commande
        $cmd = readline();
        //Parse la commande
        $argv = explode(" ", $cmd);
        $argc = count($argv);
        //Verification que la commande existe
        if(!array_key_exists($argv[0], $this->cmd)){
            echo _("Commande non reconnue par phell, taper help pour avoir la liste des commandes disponibles") . "\n";
            return false;
        }
        //Lancement de la commande
        return $this->launch($argc, $argv);
    }
    
    public function launch($argc, $argv){
        //Regarde si la commande existe dans les class deja chargée
        if(array_key_exists($argv[0], $this->cmdClass)){
            //Appel de la methode associé
            $obj = $this->class[$this->cmdClass[$argv[0]]['obj']];
            $method = $this->cmdClass[$argv[0]]['method'];
            $res = $obj->$method($argc, $argv);
        }
    }
    
    /* === Commandes intégrées au phell === */
    
    /**
     * Affiche toutes les commandes disponibles
     */
    protected function help(){
        echo "\n";
        foreach ($this->cmd as $cmd => $desc){
            echo $cmd;
            for($i = strlen($cmd); $i < 21; $i++){
                echo " ";
            }
            echo $desc . "\n";
        }
        echo "\n";
        return self::SUCCESS;
    }
    
    protected function exe($argc, $argv){
        //Retire le nom de la commande phell
        unset($argv[0]);
        //Execute la commande
        $output = [];
        exec(implode(" ", $argv), $output);
        //Affiche le resultat
        if(!empty($output)){
            echo "\n" . implode("\n", $output) . "\n";
        }
        return self::SUCCESS;
    }
    
    protected function quit(){
        $this->active = false;
    }
    
    /* === Getter/Setter === */
    
    function isActive() {
        return $this->active;
    }
    
}
