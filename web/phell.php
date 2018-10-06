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

//Ajout commande version web
$phell->addHelp("fullscreen", _("(De)active le mode plein ecran"));

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
                remote_cmd_list_url: 'web/commands.php',
                busy_text: '<?= _('Traitement...') ?>',
                unknown_cmd: '<?= _("Commande non reconnue, tapez help pour avoir la liste des commandes disponibles") ?>',
                external_processor: function (input, cmd) {
                    if (input == "quit") {
                        $('#cmd').html('<?= _('Fin de la session') ?>');
                        $("#cmd").attr('id', 'end');
                        return true;
                    } else if (input == "fullscreen") {
                        let res = toggleFullScreen(document.getElementById('cmd'));
                        return "Fullscreen " + res;
                    }
                    //Sinon commande phell
                    else {
                        var echo = '';
                        $.post("web/requete.php", {input: input}, function (data) {

                        }, 'json');
                        return echo;
                    }
                }
            });
            phell.setPrompt("<?= $phell->getPrompt() ?>");

            function toggleFullScreen(elem) {
                // ## The below if statement seems to work better ## if ((document.fullScreenElement && document.fullScreenElement !== null) || (document.msfullscreenElement && document.msfullscreenElement !== null) || (!document.mozFullScreen && !document.webkitIsFullScreen)) {
                if ((document.fullScreenElement !== undefined && document.fullScreenElement === null) || (document.msFullscreenElement !== undefined && document.msFullscreenElement === null) || (document.mozFullScreen !== undefined && !document.mozFullScreen) || (document.webkitIsFullScreen !== undefined && !document.webkitIsFullScreen)) {
                    if (elem.requestFullScreen) {
                        elem.requestFullScreen();
                    } else if (elem.mozRequestFullScreen) {
                        elem.mozRequestFullScreen();
                    } else if (elem.webkitRequestFullScreen) {
                        elem.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
                    } else if (elem.msRequestFullscreen) {
                        elem.msRequestFullscreen();
                    }
                    return "on";
                } else {
                    if (document.cancelFullScreen) {
                        document.cancelFullScreen();
                    } else if (document.mozCancelFullScreen) {
                        document.mozCancelFullScreen();
                    } else if (document.webkitCancelFullScreen) {
                        document.webkitCancelFullScreen();
                    } else if (document.msExitFullscreen) {
                        document.msExitFullscreen();
                    }
                    return 'off';
                }
            }
        </script>
    </body>
</html>