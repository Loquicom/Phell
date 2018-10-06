<?php

//Securite
defined("PHELL") OR exit('Direct access not allowed');

/**
 * Description of Phell
 *
 * @author Loquicom <contact@loquicom.fr>
 */
class Phell {

    /**
     * Constante
     */
    const VER = 0.2;

    /**
     * Constante du mode d'utilisation
     */
    const CLI = 1;
    const WEB = 2;

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
    protected static $dir = [];

    /**
     * Indique si le scan des fichiers de cmd est recursif
     * @var boolean
     */
    protected static $recursive_scan = true;
    
    /**
     * Le mode de fonctionnement actuel du phell
     * @var int
     */
    protected static $mode = 1;
    
    /**
     * Le prompt du CLI
     * @var string
     */
    protected $prompt = "Phell> ";

    /**
     * Liste des commandes dispo
     * @var array[]
     */
    protected $cmd = ['help' => ['desc' => 'Affiche les commandes disponible', 'path' => 'class'], 'quit' => ['desc' => 'Ferme le phell', 'path' => 'class']];

    /**
     * Liste des class deja chargée et leur instance
     * @var array[]
     */
    protected $class = [];

    /**
     * Liste des method pour une commande dans une class
     * @var array[] 
     */
    protected $cmdClass = ['help' => ['obj' => 'phell', 'method' => 'help'], 'quit' => ['obj' => 'phell', 'method' => 'quit']];

    /**
     * Indique si le phell est actif
     * @var boolean
     */
    protected $active = true;

    /* === Constructeur & initialisation === */

    public function __construct($argv = null) {
        //Ajoute le phell dans les class avec des commandes
        $this->class['phell'] = $this;
        //Lecture des fichiers de commandes
        if (!empty(static::$dir)) {
            foreach (static::$dir as $dir) {
                if (file_exists($dir)) {
                    $this->scanfiles($dir);
                }
            }
        }
        //Si il n'y qu'une commade à lancer
        if ($argv != null) {
            //Verification que la commande existe
            if (!array_key_exists($argv[0], $this->cmd)) {
                echo _("Commande non reconnue par phell, taper help pour avoir la liste des commandes disponibles") . "\n";
                return $this;
            }
            //Lancement de la commande
            $this->launch(count($argv), $argv);
        }
        //Affichage info Phell
        echo "\nPhell CLI by Loquicom\nV-" . static::VER . " alpha, No warranty\n\n";
    }

    protected function scanfiles($dir, $isFile = false) {
        if ($isFile) {
            $files = [$dir];
        } else {
            $files = array_diff(scandir($dir), ['.', '..']);
        }
        foreach ($files as $file) {
            $file = str_replace(" ", "_", $file);
            //Si ce n'est pas un fichier php
            if (substr($file, strlen($file) - 3) != 'php') {
                continue;
            }
            //Si c'est un dossier
            if (is_dir($file)) {
                //Si scan recursif
                if (static::$recursive_scan) {
                    $this->scanfiles($dir . '/' . $file);
                }
                continue;
            }
            //Analyse du fichier (class ou non class)
            $name = '';
            $noDesc = false;
            if (strpos($file, ".class.") !== false) {
                //Class
                require $dir . '/' . $file;
                //Regarde si il y a une methode qui indique les commandes gérées par la class
                $name = str_replace(".class.php", "", $file);
                try {
                    $obj = new $name();
                    if (method_exists($obj, "list")) {
                        $noDesc = true;
                        //Recup liste des méthodes avec description
                        $list = $obj->list();
                        if (!is_array($list)) {
                            continue;
                        }
                        foreach ($list as $cmd => $info) {
                            //Adaptation tableau
                            if (!is_array($info)) {
                                $info = ['method' => $info];
                            }
                            if (!isset($info['method'])) {
                                //Non executable par phell
                                continue;
                            }
                            //Ajout
                            $this->cmd[$cmd] = ['desc' => ((isset($info['desc'])) ? $info['desc'] : 'No description'), 'path' => 'class'];
                            $this->class[$name] = $obj;
                            $this->cmdClass[$cmd] = ['obj' => $name, 'method' => $info['method']];
                        }
                    } else if (method_exists($obj, "run")) {
                        $this->cmd[$name] = ['desc' => 'No descrition', 'path' => 'class'];
                        $this->class[$name] = $obj;
                        $this->cmdClass[$name] = ['obj' => $name, 'method' => 'run'];
                    } else {
                        //Non executable par phell
                        continue;
                    }
                } catch (Exception $ex) {
                    continue;
                }
            } else {
                //Non class
                $name = substr($file, 0, strlen($file) - 4);
                $this->cmd[$name] = ['desc' => 'No descrition', 'path' => $dir . '/' . $file];
            }
            //Regarde si il y a une description dans le fichier
            if (!$noDesc) {
                $fd = fopen($dir . '/' . $file, 'r');
                $str = fgets($fd);
                $str = fgets($fd);
                if (substr($str, 0, 3) == '///') {
                    //Ajoute la description
                    $this->cmd[$name]['desc'] = rtrim(substr($str, 3), "\n");
                }
                fclose($fd);
            }
        }
    }

    /* === Commande gestion === */

    public function cli() {
        //Ecriture
        echo $this->prompt;
        //Attente commande
        $cmd = readline();
        //Parse la commande
        $argv = explode(" ", $cmd);
        $argc = count($argv);
        //Verification que la commande existe
        if (!array_key_exists($argv[0], $this->cmd)) {
            echo _("Commande non reconnue par phell, taper help pour avoir la liste des commandes disponibles") . "\n";
            return false;
        }
        //Lancement de la commande
        return $this->launch($argc, $argv);
    }

    protected function launch($argc, $argv) {
        //Regarde si la commande existe dans les class deja chargée
        if (array_key_exists($argv[0], $this->cmdClass)) {
            //Appel de la methode associé
            $obj = $this->class[$this->cmdClass[$argv[0]]['obj']];
            $method = $this->cmdClass[$argv[0]]['method'];
            return $obj->$method($argc, $argv);
        }
        //Sinon charge le script de la commande
        return $this->run($argv[0], $argc, $argv);
    }

    protected function run($cmd, $argc, $argv) {
        //Charge le fichier de la commande
        try {
            require $this->cmd[$cmd]['path'];
        } catch (Exception $ex) {
            return static::ERROR;
        }
        //Retour
        if (isset($return)) {
            return $return;
        } else {
            return static::SUCCESS;
        }
    }

    /* === Commandes intégrées au phell === */

    /**
     * Affiche toutes les commandes disponibles
     * @return int - Etat
     */
    protected function help() {
        echo "\n";
        foreach ($this->cmd as $cmd => $data) {
            echo $cmd;
            for ($i = strlen($cmd); $i < 21; $i++) {
                echo " ";
            }
            echo $data['desc'] . "\n";
        }
        return self::SUCCESS;
    }

    /**
     * Ferme le Phell
     * @return int - Etat
     */
    protected function quit() {
        $this->active = false;
        return self::SUCCESS;
    }

    /* === Getter/Setter === */

    public function isActive() {
        return $this->active;
    }
    
    function getPrompt() {
        return $this->prompt;
    }

    function setPrompt(string $prompt) {
        $this->prompt = $prompt . " ";
    }

    /* === Config === */

    public function addCmd(string $filename) {
        $this->scanfiles($filename, true);
    }

    public static function addDir(string $dir) {
        if (file_exists($dir)) {
            static::$dir[] = $dir;
        }
    }

    public static function setRecursiveScan(bool $bool) {
        static::$recursive_scan = $bool;
    }
    
    public static function setMode(int $newmode){
        if(in_array($newmode, [static::CLI, static::WEB])){
            static::$mode = $newmode;
        }
    }

}
