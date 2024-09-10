<?php

require_once(__DIR__ . '/../system/vendor/autoload.php');

use App\Route;
use Library\Log;

date_default_timezone_set('America/Sao_Paulo');

set_exception_handler(function (Throwable $exception) {
    Log::write(sprintf(
        'Exceção: %s - Arquivo: %s - Linha: %s',
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine()
    ));
});

set_error_handler(function ($errorLevel, $errorMessage, $errorFile, $errorLine) {
    if (error_reporting() === 0) {
        return false;
    }

    switch ($errorLevel) {
        case E_NOTICE:
        case E_USER_NOTICE:
            $error = 'Notice';
            break;
        case E_WARNING:
        case E_USER_WARNING:
            $error = 'Warning';
            break;
        case E_ERROR:
        case E_USER_ERROR:
            $error = 'Fatal Error';
            break;
        default:
            $error = 'Unknown';
            break;
    }

    Log::write(sprintf(
        '%s: %s - Arquivo: %s - Linha: %s',
        $error,
        $errorMessage,
        $errorFile,
        $errorLine
    ));

    return true;
});

function loadEnvironmentVariables()
{
    if (!file_exists(__DIR__ . '/../.env')) {
        throw new Exception('Arquivo .env não encontrado no projeto!');
    }

    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        if (strpos($line, '#') !== false) {
            $line = strstr($line, '#', true);
        }

        $line = trim($line);

        if (!empty($line)) {
            list($key, $value) = explode('=', $line, 2) + [NULL, NULL];

            if ($key !== NULL && $value !== NULL) {
                $_ENV[trim($key)] = trim($value);
            }
        }
    }

    $requested = [
        'DB_HOST',
        'DB_NAME',
        'DB_USER',
        'DB_PASSWORD',
        'MAIL_ADDRESS',
        'MAIL_PASSWORD',
        'MAIL_USERNAME',
        'UPLOAD_NAME',
        'UPLOAD_EMAIL',
        'UPLOAD_TOKEN',
        'UPLOAD_PATH'
    ];

    $diff = array_diff($requested, array_keys($_ENV));

    if (!empty($diff)) {
        throw new Exception('Variáveis de ambiente não encontradas no arquivo .env!');
    }
}

loadEnvironmentVariables();

$route = new Route();
