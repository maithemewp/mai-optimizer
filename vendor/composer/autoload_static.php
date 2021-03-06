<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7d43458237011e0a3ca85d7dfd30785a
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'MaiOptimizer\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'MaiOptimizer\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'J' => 
        array (
            'JShrink' => 
            array (
                0 => __DIR__ . '/..' . '/tedivm/jshrink/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7d43458237011e0a3ca85d7dfd30785a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7d43458237011e0a3ca85d7dfd30785a::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit7d43458237011e0a3ca85d7dfd30785a::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
