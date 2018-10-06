<?php

//Securite
defined("PHELL") OR exit('Direct access not allowed');

//On indique qu'on utilise en mode web
Phell::setMode(Phell::WEB);

//Lancement Phell
ob_start();
$phell = new Phell();
$output = newLine(ob_get_contents());
ob_end_clean();

//Mise en session
session_name('WebPhell');
session_start();
$_SESSION['phell'] = $phell;

//Page web
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Cmd example</title>
        <link rel="stylesheet" type="text/css" href="web/css/cmd.min.css">
        <style type="text/css">
        </style>
    </head>
    <body id="cmd">
        <?= $output ?>
        <script type="text/javascript" src="web/js/jquery.min.js"></script>
        <script type="text/javascript" src="web/js/cmd.min.js"></script>
        <script type="text/javascript">
            var phell = new Cmd({
                selector: '#cmd',
                remote_cmd_list_url: 'web/commands.php'
            });
            phell.setPrompt("<?= $phell->getPrompt() ?>");
        </script>
    </body>
</html>