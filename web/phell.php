<?php
//Securite
defined("PHELL") OR exit('Direct access not allowed');

//On indique qu'on utilise en mode web
Phell::setMode(Phell::WEB);

//Lancement Phell
ob_start();
$phell = new Phell();
$output = adapt(ob_get_contents());
ob_end_clean();

//Configuration instance Phell
if (trim($config['prompt']) != '') {
    $phell->setPrompt($config['prompt']);
}

//Ajout commande version web
$phell->addHelp("fullscreen", _("(Des)active le mode plein ecran"));
$phell->addHelp("refresh", _("Recharge la page"));
$phell->addHelp("reload", _("Alias de refresh"));
$phell->addHelp("clear", _("Vide le terminal"));

//Mise en session
session_name('WebPhell');
session_start();
$_SESSION['phell'] = $phell;

//Page web
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Phell CLI</title>
        <link rel="stylesheet" type="text/css" href="web/css/cmd.min.css">
        <link rel="icon" href="web/favicon.ico">
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
                    //Interpretation commande
                    switch (input) {
                        case "quit":
                            $('#cmd').html('<?= _('Fin de la session') ?>');
                            $("#cmd").attr('id', 'end');
                            return true;
                        case "fullscreen":
                            let res = toggleFullScreen(document.getElementById('cmd'));
                            return "Fullscreen " + res;
                        case "refresh":
                        case "reload":
                            location.reload();
                            return true;
                        default:
                            //Commande phell standard
                            $.post("web/requete.php", {input: input}, function (data) {
                                if (data.etat) {
                                    //Creation objet data
                                    var object = {
                                        cmd_out: data.msg
                                    };
                                    if (data.pass != undefined) {
                                        object.show_pass = data.pass;
                                    }
                                    if (data.link != undefined) {
                                        object.openWindow = data.link;
                                    }
                                    cmd.handleResponse(object);
                                } else {
                                    var msg = '<?= _("Commande non reconnue, tapez help pour avoir la liste des commandes disponibles") ?>';
                                    if(data.msg.trim() != ''){
                                      msg = data.msg;  
                                    }
                                    cmd.handleResponse({
                                        cmd_out: msg,
                                        show_pass: false
                                    });
                                }
                            }, 'json');
                            return true;
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
