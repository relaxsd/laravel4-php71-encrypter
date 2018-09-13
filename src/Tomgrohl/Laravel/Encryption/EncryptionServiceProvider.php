<?php

namespace Tomgrohl\Laravel\Encryption;

use Illuminate\Support\ServiceProvider;

/**
 * Class EncryptionServiceProvider
 *
 * @package Tomgrohl\Laravel\Encryption
 */
class EncryptionServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bindShared('encrypter', function ($app) {

            // Before creating an Encrypter, we need to make sure
            // the constants MCRYPT_RIJNDAEL_128 and MCRYPT_MODE_CBC are set,
            // because the base class (Illuminate\Encryption\Encrypter) uses them.
            self::defineIfNotDefined('MCRYPT_RIJNDAEL_128', "rijndael-128");
            self::defineIfNotDefined('MCRYPT_MODE_CBC', "cbc");

            if ($app['config']->has('app.cipher')) {

                return new Encrypter(
                    $app['config']['app.key'],
                    $app['config']['app.cipher']
                );
            } else {
                return new Encrypter($app['config']['app.key']);
            }
        });

        $this->app->alias('encrypter', Encrypter::class);
    }

    private static function defineIfNotDefined($name, $value)
    {
        if (!defined($name)) {
            define($name, $value);
        }
    }

}
