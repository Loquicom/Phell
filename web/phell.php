<?php

//On indique qu'on utilise en mode web
Phell::setMode(Phell::WEB);

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
        <script type="text/javascript" src="web/js/jquery.min.js"></script>
        <script type="text/javascript" src="web/js/cmd.min.js"></script>
        <script type="text/javascript">
            var phell = new Cmd({
                selector: '#cmd'
            });
        </script>
    </body>
</html>