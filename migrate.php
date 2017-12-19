<?php
require_once('vendor/autoload.php');
use Illuminate\Database\Capsule\Manager as Capsule;
use app\core\DB;

DB::connect();

Capsule::schema()->create('users', function ($table) {
    $table->increments('userID');
    $table->string('email')->unique();
    $table->string('username');
    $table->string('firstname');
    $table->string('lastname');
    $table->string('currencyCode');
    $table->string('languageCode');
    $table->string('dateOfBirth');
    $table->string('gender');
    $table->timestamps();
});
Capsule::schema()->create('addresses', function ($table) {
    $table->increments('id');
    $table->integer('user_id')->unsigned()->unique();
    $table->string('street');
    $table->string('houseNumber');
    $table->string('postalCode');
    $table->string('city');
    $table->string('countryCode2');
    $table->string('telephoneNumber');
    $table->timestamps();

    $table->foreign('user_id')->references('userID')->on('users');
});
Capsule::schema()->create('payment_methods', function ($table) {
    $table->increments('id');
    $table->string('name');
    $table->integer('num');
    $table->integer('num_repeated');
    $table->integer('creationTypeID');
    $table->integer('creationTypeID_repeated');
    $table->integer('refunded');
    $table->timestamps();
});
Capsule::schema()->create('orders', function ($table) {
    $table->increments('id');
    $table->integer('user_id')->unsigned();
    $table->string('count_ven');
    $table->string('paymentID');
    $table->string('state_id');
    $table->integer('method_id')->unsigned();
    $table->string('state')->default('none');
    $table->timestamps();

    $table->foreign('method_id')->references('id')->on('payment_methods');
    $table->foreign('user_id')->references('userID')->on('users');
});
Capsule::schema()->create('payment_accounts', function ($table) {
    $table->increments('id');
    $table->string('payment_id')->unique();
    $table->integer('method_id')->unsigned();
    $table->integer('user_id')->unsigned();
    $table->timestamps();

    $table->foreign('method_id')->references('id')->on('payment_methods');
    $table->foreign('user_id')->references('userID')->on('users');
});
