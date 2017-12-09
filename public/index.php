<?php
require_once('../sys/init.php');
use app\core\Router;

require_once '../app/http/routes.php';
Router::dispatch();

exit;
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'kalixa',
    'username'  => 'root',
    'password'  => '100500',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();


$document = new lib\Document();
$users = Capsule::table('users')->get();
dump($users);
?>
<a href="orders.php">List orders</a><br>
<a href="/pay/">Pay</a><br>