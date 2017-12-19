<?php
session_start();
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

use app\core\Router;
use app\core\DB;
use Illuminate\Database\Capsule\Manager as Capsule;
use app\models\User;
use app\models\Address;

DB::connect();

function dump($array) {
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}
  
function venToUsd($ven)
{
return $ven * 0.1;
}
require_once '../app/http/routes.php';
Router::dispatch();