<?php


namespace App\Services;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class MyLogs
{
    private $log;
    /**
     * MyLogs constructor.
     * @param $log
     */
    public function __construct($name)
    {
        $this->log = new Logger($name);
        $this->log->pushHandler(
            new StreamHandler('../logs/proyectoFinal.logs', Logger::WARNING));
    }

    public function add($message){
        $this->log->log(Logger::WARNING, $message);
    }
}