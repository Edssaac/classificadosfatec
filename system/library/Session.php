<?php

namespace Library;

class Session
{
    public static function init(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public static function isLogged(): bool
    {
        self::init();

        if (!isset($_SESSION['user']['user_id']) || empty($_SESSION['user']['user_id'])) {
            return false;
        }

        return true;
    }

    public static function isAdminstrator(): bool
    {
        self::init();

        if (isset($_SESSION['user']['admin']) && $_SESSION['user']['admin']) {
            return true;
        }

        return false;
    }

    public static function getLoggedUser(): array
    {
        self::init();

        return (self::isLogged()) ? $_SESSION['user'] : [];
    }

    public static function login(array $user): void
    {
        self::init();

        $_SESSION['user'] = [
            'user_id' => $user['user_id'],
            'name' => $user['name'],
            'admin' => $user['admin']
        ];
    }

    public static function logout(): void
    {
        self::init();

        unset($_SESSION['user']);

        header('location: /');
        exit;
    }
}