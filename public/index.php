<?php

require_once(__DIR__ . '/../system/vendor/autoload.php');

use App\Route;
use Library\Session;
use Library\Log;

date_default_timezone_set('America/Sao_Paulo');

set_exception_handler(function (Throwable $exception) {
    Log::write(sprintf(
        'Exceção: %s - Arquivo: %s - Linha: %s',
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine()
    ));

    $_SESSION['INTERNAL_SITUATION'] = 500;

    Session::logout();
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

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();

    $dotenv->required([
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
        'UPLOAD_PATH',
        'IMAGE_BASE_PATH'
    ])->notEmpty();
}

Session::init();

if (!isset($_SESSION['INTERNAL_SITUATION'])) {
    loadEnvironmentVariables();
}

$route = new Route();
