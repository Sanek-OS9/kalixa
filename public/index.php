<?php
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set("display_errors", 1);
define("H", $_SERVER['DOCUMENT_ROOT']);
# доступные языки, используется в роутах
define('AVAILABLE_LANG', '(uk|en|ru|ko)');
/*
* Test Stan
*/
define('USER_LOGIN', 'PSHubCultureServicesSystemUser');
define('USER_PASS', 'bermuda88!');
define('merchantID', 'HubCultureServices');
define('shopID', 'HubCultureServices');

require_once(H . '/vendor/autoload.php');

use app\core\{Router,DB};
use Illuminate\Database\Capsule\Manager as Capsule;
use app\models\{User,Address};

DB::connect();

function dump($array) {
    echo '<pre>';
    print_r($array);
    echo '<pre>';
}
  
function venToUsd($ven)
{
return $ven * 0.1;
}
  
$user = User::firstOrCreate([
    'username' => 'Yakov', 
    'firstname' => 'Yakov',
    'lastname' => 'Litvak',
    'currencyCode' => 'USD',
    'languageCode' => 'EN',
    'email' => 'yakov.litvak@qbex.io',
    'dateOfBirth' => '1989-09-28T00:00:00',
    'gender' => 'Male',
    'userSessionID' => '343',
    'creationTypeID' => '1',
]);
$address = Address::firstOrCreate([
    'user_id' => $user->userID,
    'street' => 'Marxergasse',
    'houseNumber' => '34b',
    'postalCode' => '1024',
    'city' => 'Vienna',
    'countryCode2' => 'AT',
    'telephoneNumber' => '0064765475',
]);

//echo $user->address->street;
//$ank = User::find(3);
//dump($ank); 
require_once '../app/http/routes.php';
Router::dispatch();