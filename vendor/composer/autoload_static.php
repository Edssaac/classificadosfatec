<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit871383921ca2a9df177f560d1266bce6
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'M' => 
        array (
            'MF\\' => 3,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'MF\\' => 
        array (
            0 => __DIR__ . '/..' . '/MF',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/App',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit871383921ca2a9df177f560d1266bce6::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit871383921ca2a9df177f560d1266bce6::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit871383921ca2a9df177f560d1266bce6::$classMap;

        }, null, ClassLoader::class);
    }
}