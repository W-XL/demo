<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerAutoloaderInit8bf1e4f9c5bbb88a25d2be6e225e473e
{
    public static $files = array (
        'ad155f8f1cf0d418fe49e248db8c661b' => __DIR__ . '/..' . '/react/promise/src/functions_include.php',
        'f084d01b0a599f67676cffef638aa95b' => __DIR__ . '/..' . '/smarty/smarty/libs/bootstrap.php',
        '6dbd3e6118d21074932eabe9324f542e' => __DIR__ . '/../..' . '/common/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'React\\Promise\\' => 14,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'G' => 
        array (
            'GuzzleHttp\\Stream\\' => 18,
            'GuzzleHttp\\Ring\\' => 16,
        ),
        'E' => 
        array (
            'Elasticsearch\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'React\\Promise\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/promise/src',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'GuzzleHttp\\Stream\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/streams/src',
        ),
        'GuzzleHttp\\Ring\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/ringphp/src',
        ),
        'Elasticsearch\\' => 
        array (
            0 => __DIR__ . '/..' . '/elasticsearch/elasticsearch/src/Elasticsearch',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'PHPExcel' => 
            array (
                0 => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerAutoloaderInit8bf1e4f9c5bbb88a25d2be6e225e473e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerAutoloaderInit8bf1e4f9c5bbb88a25d2be6e225e473e::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerAutoloaderInit8bf1e4f9c5bbb88a25d2be6e225e473e::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
