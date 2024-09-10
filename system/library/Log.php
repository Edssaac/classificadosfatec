<?php

namespace Library;

class Log
{
    public static function write(string $message): void
    {
        $log_directory = __DIR__ . '/../logs/';
        $log_file = $log_directory . 'app.log';

        if (!file_exists($log_directory) || !is_dir($log_directory)) {
            mkdir($log_directory, 0777, true);
        }

        $fp = fopen($log_file, 'a+');

        if ($fp !== false) {
            fwrite($fp, sprintf("[%s] %s\n", date('d/m/Y H:i:s'), $message));
            fclose($fp);
        }
    }
}
