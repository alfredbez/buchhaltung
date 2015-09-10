<?php

class Logger
{
    private $logfile;

    public function __construct($file = false)
    {
        if ($file) {
            $this->logfile = $file;
        } else {
            $this->logfile = '';
            $this->logfile .= __DIR__;
            $this->logfile .= '../../../';
            $this->logfile .= 'log';
        }
    }

    private function log($type, $msg)
    {
        $content = date('Y-m-d H:i:s') . " [$type]: $msg\n";
        file_put_contents($this->logfile, $content, FILE_APPEND);
    }

    public function __call($name, $arguments)
    {
        return $this->log($name, $arguments[0]);
    }
}
