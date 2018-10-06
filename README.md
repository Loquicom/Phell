# Phell (PHP Shell)
Phell est une interface en ligne de commande (CLI) utilisable directement sur un terminal ou via un serveur web. Phell est écrit en PHP et a pour objectif de permettre l'exécution simple de script PHP



## Utilisation

Phell utilise un fichier de config nommé config.json à la racine du projet. Le fichier est composé de 3 entré.

- `dir` : Tableau avec les chemins vers les fichiers contenant les scripts PHP (par defaut cmd/)
- `recursive` : Indique si le scan dans les dossiers `dir` est recursif (càd s'il y a un dossier dans un des dossiers de dir il sera aussi scanné)
- `prompt` : L'affichage du prompt



Phell reconnait trois types du script PHP comme commande executable :

- [Les scripts simples](#Scripts simples)
- [Les class avec une seule commandes](#Class avec une commande)
- [Les class avec plusieurs commandes](#Class avec plusieurs commandes)

Dans tous les cas les variables `$argc` et `$argv` sont accessibles comme lors de l'appel d'un script php en CLI



## Scripts simples

Les scripts simples sont des fichiers qui n'ont besoin que d'être chargé (par `require` ou `include`) pour s'exécuter. Un exemple est le fichier [exe.php](https://github.com/Loquicom/Phell/blob/master/cmd/exe.php) de base dans Phell. Le fichier sera chargé à chaque fois que la commande est appelée. Dans les fichiers `$this` est disponible et correspond à l'instance de Phell. Le nom de la commande est le nom du fichier sans le .php. Pour ajouter une description il suffit d'ajouter un /// suivi de la description à la 2éme ligne du fichier (juste sous le <?php).

*Exemple de description :*

```php
<?php
///Description de la commande

$a = $argc - 1;
/*
 ...
 */
echo $a;
```

Si la description est ailleurs elle ne sera pas prise en compte



## Class avec une commande

Les fichiers utilisant des class doivent comporter .class.php dans leur nom de fichier *(exemple : test.class.php)*. Les fichiers et les class seront chargés à l'instanciation de Phell. La class doit porter le même nom que le fichier (sans le .class.php), n'avoir aucun paramètre obligatoire dans le constructeur, et avoir une méthode `run` qui permet de lancer la commande. Le retour attendu est un boolean ([une chaine json est aussi possible avec le mode web](#Retour JSON en mode Web)). Le nom de la commande sera le nom du fichier dans le .class.php . La description de la commande fonctionne de la même manière que pour les scripts simples avec un /// suivi de la description sur la deuxième ligne.

*Exemple de class avec une commande :*

```php
<?php
///Description de la commande
    
class exemple{
	
    /**
     * Lance la commande
     * @param int $argc Le nombre d'argument
     * @param string[] $argv Les arguments
     * @param Phell $phell L'instance de Phell
     */
    public function run($argc, $argv, $phell){
        /* ... */
        $this->autre();
        /* ... */
        return true;
    }
    
    public function autre(){
    	/* ... */
    }
    
}
```



## Class avec plusieurs commandes

Les fichiers utilisant des class doivent comporter .class.php dans leur nom de fichier *(exemple : test.class.php)*. Les fichiers et les class seront chargés à l'instanciation de Phell. La class doit porter le même nom que le fichier (sans le .class.php), n'avoir aucun paramètre obligatoire dans le constructeur, et avoir une méthode `phell` qui permet de lister les commandes disponibles dans la class. Le retour des commandes attendu est un boolean ([une chaine json est aussi possible avec le mode web](#Retour JSON en mode Web)). Le nom et la description de la commande sont indiqué dans la méthode `phell` (voir exemple en dessous).



*Exemple class avec plusieurs commandes :*

```php
<?php

class exemple {
    
    /**
     * Liste les commandes utilisable par Phell
     * @return array Liste des commandes
     */
    public function phell(){
        return [
            'nomCommande1' => [
            	'method' => 'nomMethodeUtilisé', 
            	'desc' => 'Description commande'
            ],
            'nomCommande2' => 'nomMethodeUtilisé',
            'exemple' => [
            	'method' => 'test', 
            	'desc' => 'Exemple'
            ]
        ];
    }
    
    /**
     * Lance la commande
     * @param int $argc Le nombre d'argument
     * @param string[] $argv Les arguments
     * @param Phell $phell L'instance de Phell
     */
    public function test($argc, $argv, $phell){
        echo 'test';
        return true;
    }
    
}
```

 Le nom de la commande est la clef dans le tableau. Après deux possibilités soit juste mettre le nom de la méthode à appelé, soit mettre un tableau avec la méthode et la description de la commande. Un exemple est disponible dans le fichier [exemple.class.php](https://github.com/Loquicom/Phell/blob/master/cmd/exemple.class.php).



## Retour JSON en mode Web

En mode web les méthodes peuvent retourner un JSON à la place d'un boolean. le json peut avoir jusqu'à deux attributs :

- `link` : Lien vers un site web qui sera ouvert dans un nouvel onglet
- `show` : Boolean qui indique si l'on doit cacher ou afficher ce que tape l'utilisateur

Un exemple est disponible dans la méthode web du fichier [exemple.class.php](https://github.com/Loquicom/Phell/blob/master/cmd/exemple.class.php).