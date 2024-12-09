<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitbaf0b1b659e688a64051c5ee9742a77e
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInitbaf0b1b659e688a64051c5ee9742a77e', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitbaf0b1b659e688a64051c5ee9742a77e', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitbaf0b1b659e688a64051c5ee9742a77e::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}