<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5eff1e898c82733f5bf8f056e6de4a01
{
    public static $files = array (
        'ac1faf2d05473abdd0859e9eafa444ac' => __DIR__ . '/../..' . '/registration.php',
    );

    public static $prefixLengthsPsr4 = array (
        'E' => 
        array (
            'Ecomteck\\ProductAttachment\\' => 27,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Ecomteck\\ProductAttachment\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5eff1e898c82733f5bf8f056e6de4a01::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5eff1e898c82733f5bf8f056e6de4a01::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
