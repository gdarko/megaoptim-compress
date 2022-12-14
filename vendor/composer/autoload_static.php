<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit36aaa57987611c7fd2c2b2883e896d84
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'MegaOptim\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'MegaOptim\\' => 
        array (
            0 => __DIR__ . '/..' . '/megaoptim/megaoptim-php/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit36aaa57987611c7fd2c2b2883e896d84::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit36aaa57987611c7fd2c2b2883e896d84::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
