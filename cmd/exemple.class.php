<?php

class exemple{
    
    public function phell(){
        return [
            'ping' => ['method' => 'ping', 'desc' => 'pong'],
            'pong' => ['method' => 'pong', 'desc' => 'ping'],
            'print' => ['method' => 'output', 'desc' => 'Affiche sur le terminal une phrase'],
            'say' => ['method' => 'output', 'desc' => 'Repete une phrase sur le terminal']
        ];
    }
    
    public function ping(){
        echo "pong";
        return true;
    }
    
    public function pong(){
        echo "ping";
        return true;
    }
    
    public function output($argc, $argv){
        array_shift($argv);
        echo implode(" ", $argv) . "\n";
        return true;
    }
    
}