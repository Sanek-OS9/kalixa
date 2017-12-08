<?php
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set("display_errors", 1);

spl_autoload_register(function ($class) {
  // echo $_SERVER['DOCUMENT_ROOT'] . '/' . str_replace('\\', '/', $class) . '.class.php<br>';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/' . str_replace('\\', '/', $class) . '.class.php';
});


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

