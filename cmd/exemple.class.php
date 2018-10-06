<?php

class exemple {

    public function phell() {
        return [
            'ping' => ['method' => 'ping', 'desc' => 'pong'],
            'pong' => ['method' => 'pong', 'desc' => 'ping'],
            'print' => ['method' => 'output', 'desc' => 'Affiche sur le terminal une phrase'],
            'say' => ['method' => 'output', 'desc' => 'Repete une phrase sur le terminal'],
            'web' => ['method' => 'web', 'desc' => 'Retour special pour le type web']
        ];
    }

    public function ping() {
        echo "pong";
        return true;
    }

    public function pong() {
        echo "ping";
        return true;
    }

    public function output($argc, $argv) {
        array_shift($argv);
        echo implode(" ", $argv) . "\n";
        return true;
    }

    public function web($argc, $argv) {
        echo "Ne fonctionne que sur le web";
        $res = ['link' => 'https://loquicom.fr'];
        if (isset($argv[1])) {
            if ($argv[1] == "false") {
                $res['show'] = false;
            } else {
                $res['show'] = true;
            }
        }
        return json_encode($res);
    }

}
