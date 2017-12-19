<?php
require_once('vendor/autoload.php');
use app\core\DB;
use app\models\Payment_method;

DB::connect();

$payment_method = new Payment_method;
$payment_method->name = 'ECMC Deposit';
$payment_method->num = '1';
$payment_method->num_repeated = '1';
$payment_method->creationTypeID = '1';
$payment_method->creationTypeID_repeated = '3';
$payment_method->refunded = '89';
$payment_method->save();

$payment_method = new Payment_method;
$payment_method->name = 'VISA Deposit';
$payment_method->num = '2';
$payment_method->num_repeated = '2';
$payment_method->creationTypeID = '1';
$payment_method->creationTypeID_repeated = '3';
$payment_method->refunded = '88';
$payment_method->save();

$payment_method = new Payment_method;
$payment_method->name = 'Maestro Deposit';
$payment_method->num = '73';
$payment_method->num_repeated = '73';
$payment_method->creationTypeID = '1';
$payment_method->creationTypeID_repeated = '1';
$payment_method->refunded = '90';
$payment_method->save();
