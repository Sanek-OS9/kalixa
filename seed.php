<?php
require_once('vendor/autoload.php');
use app\core\DB;
use app\models\Payment_method;

DB::connect();

$payment_method = new Payment_method;
$payment_method->name = 'ECMC Deposit';
$payment_method->num = '1';
$payment_method->save();

$payment_method = new Payment_method;
$payment_method->name = 'VISA Deposit';
$payment_method->num = '2';
$payment_method->save();

$payment_method = new Payment_method;
$payment_method->name = 'Maestro Deposit';
$payment_method->num = '73';
$payment_method->save();
