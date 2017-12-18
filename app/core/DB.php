<?php
namespace app\core;

use Illuminate\Database\Capsule\Manager as Capsule;

abstract class DB{
    public static function connect()
    {
        $capsule = new Capsule;

        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'kalixa',
            'username'  => 'root',
            'password'  => 'sl123',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        return;
    }
}