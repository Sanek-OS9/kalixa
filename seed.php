<?php
require_once('vendor/autoload.php');
use app\core\DB;
use app\models\{Payment_method,User,Address};

DB::connect();

Payment_method::insert([
  ['name' => 'ECMC Deposit', 'num' => 1, 'num_repeated' => 1, 'creationTypeID' => 1, 'creationTypeID_repeated' => 3, 'refunded' => 89],
  ['name' => 'VISA Deposit', 'num' => 2, 'num_repeated' => 2, 'creationTypeID' => 1, 'creationTypeID_repeated' => 3, 'refunded' => 88],
  ['name' => 'Maestro Deposit', 'num' => 73, 'num_repeated' => 73, 'creationTypeID' => 1, 'creationTypeID_repeated' => 1, 'refunded' => 90],
]);

$user = User::create([
  'username' => 'Yakov', 
  'firstname' => 'Yakov',
  'lastname' => 'Litvak',
  'currencyCode' => 'USD',
  'languageCode' => 'EN',
  'email' => 'yakov.litvak@qbex.io',
  'dateOfBirth' => '1989-09-28T00:00:00',
  'gender' => 'Male',
]);
Address::create([
  'user_id' => $user->userID,
  'street' => 'Marxergasse',
  'houseNumber' => '34b',
  'postalCode' => '1024',
  'city' => 'Vienna',
  'countryCode2' => 'AT',
  'telephoneNumber' => '0064765475',
]);
