<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd3731b9796d3a1a60f752e4d9a33c4cf
{
    public static $prefixLengthsPsr4 = array (
        'K' => 
        array (
            'Katzgrau\\KLogger\\' => 17,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Katzgrau\\KLogger\\' => 
        array (
            0 => __DIR__ . '/..' . '/katzgrau/klogger/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'Psr\\Log\\' => 
            array (
                0 => __DIR__ . '/..' . '/psr/log',
            ),
        ),
    );

    public static $classMap = array (
        'Katzgrau\\KLogger\\Logger' => __DIR__ . '/..' . '/katzgrau/klogger/src/Logger.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd3731b9796d3a1a60f752e4d9a33c4cf::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd3731b9796d3a1a60f752e4d9a33c4cf::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitd3731b9796d3a1a60f752e4d9a33c4cf::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitd3731b9796d3a1a60f752e4d9a33c4cf::$classMap;

        }, null, ClassLoader::class);
    }
}