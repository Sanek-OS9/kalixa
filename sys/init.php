<?php
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set("display_errors", 1);
define("H", $_SERVER['DOCUMENT_ROOT']);
# доступные языки, используется в роутах
define('AVAILABLE_LANG', '(uk|en|ru|ko)');
require_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');
/*
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => '127.0.0.1',
    'database'  => 'kalixa',
    'username'  => 'root',
    'password'  => '100500',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();*/
/*
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
    $table->string('userSessionID');
    $table->string('creationTypeID');
    $table->timestamps();
});
*/
/*
Capsule::schema()->create('address', function ($table) {
  $table->increments('id');
  $table->string('user_id')->unique();
  $table->string('street');
  $table->string('houseNumber');
  $table->string('postalCode');
  $table->string('city');
  $table->string('countryCode2');
  $table->string('telephoneNumber');
  $table->timestamps();
});
*/
/*
* Test Stan
*/
define('USER_LOGIN', 'PSHubCultureServicesSystemUser');
define('USER_PASS', 'bermuda88!');
define('merchantID', 'HubCultureServices');
define('shopID', 'HubCultureServices');

/*
* Test kalixa
*/
// define('USER_LOGIN', 'KalixaAcceptDemoSystemUser');
// define('USER_PASS', 'KalixaAcceptDemoPassword');
// define('merchantID', 'KalixaAcceptDEMO');
// define('shopID', 'KalixaAcceptDEMO');
/*
spl_autoload_register(function ($class) {
  // echo $_SERVER['DOCUMENT_ROOT'] . '/' . str_replace('\\', '/', $class) . '.class.php<br>';
  $class = $_SERVER['DOCUMENT_ROOT'] . '/' . str_replace('\\', '/', $class) . '.class.php';
  // echo $class;
  require_once $class;
});
*/
// require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/DB.class.php');
// require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/Document.class.php');
use lib\DB;

function dump($array) {
  echo '<pre>';
  print_r($array);
  echo '<pre>';
}

function venToUsd($ven)
{
  return $ven * 0.1;
}

function getUser($user_id)
{
  $q = DB::me()->prepare("SELECT * FROM `users` WHERE `userID` = ? LIMIT 1");
  $q->execute([$user_id]);
  return $q->fetch();
}

$q_user = DB::me()->prepare("SELECT `users`.* 
  FROM `users` 
  LIMIT 1");
$q_user->execute();
if (!$user = $q_user->fetch()) {
  $q = DB::me()->prepare("INSERT INTO `users` (`username`, `firstname`, `lastname`, `currencyCode`, `languageCode`, `email`, `dateOfBirth`, `gender`, `userSessionID`, `creationTypeID`) VALUES (?,?,?,?,?,?,?,?,?,?)");
  $q->execute(['Yakov', 'Yakov', 'Litvak', 'USD', 'EN', 'yakov.litvak@qbex.io', '1989-09-28T00:00:00', 'Male', '343', '1']);
  $user_id = DB::me()->lastInsertId();

  $q = DB::me()->prepare("INSERT INTO `address` (`user_id`, `street`, `houseNumber`, `postalCode`, `city`, `countryCode2`, `telephoneNumber`) VALUES (?,?,?,?,?,?,?)");
  $q->execute([$user_id, 'Marxergasse', '34b', '1024', 'Vienna', 'AT', '0064765475']);

  echo $user_id;
}
$q = DB::me()->prepare("SELECT `street`,`houseNumber`,`postalCode`,`city`,`countryCode2`,`telephoneNumber` FROM `address` WHERE `user_id` = ? LIMIT 1");
$q->execute([$user['userID']]);
$user['address'] = $q->fetch();

