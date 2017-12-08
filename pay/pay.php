<?php
require_once('../sys/init.php');
use lib\kalixa\Kalixa;
use lib\kalixa\Order;
use lib\DB;

if (!isset($_POST['ven']) || !is_numeric($_POST['ven'])) {
  header('Location: ./');
  exit;  
}
if (!isset($_POST['paymentMethodID']) || !is_numeric($_POST['paymentMethodID'])) {
  header('Location: ./');
  exit;  
}
$payVen = $_POST['ven'];
$paymentMethodID = $_POST['paymentMethodID'];

DB::me()->beginTransaction();
$merchantTransactionID = Order::create($user['userID'], $payVen);

$kalixa = new Kalixa('initiatePayment.1');
$kalixa->xml->merchantID = merchantID;
$kalixa->xml->shopID = shopID;
$kalixa->xml->merchantTransactionID = $merchantTransactionID;
$kalixa->xml->paymentMethodID = $paymentMethodID; // 1 - ECMC Deposit, 2 - VISA Deposit, 73 - Maestro Deposit
$kalixa->xml->amount = venToUsd($payVen);
$kalixa->xml->userID = $user['userID'];

$kalixa->xml->userIP = $_SERVER['REMOTE_ADDR'];
$kalixa->xml->userSessionID = 123;
$kalixa->xml->creationTypeID = 3;

$kalixa->xml->specificPaymentData->data{0}->key = 'PaymentDescription';
$kalixa->xml->specificPaymentData->data{0}->value = 'some description';
$kalixa->xml->specificPaymentData->data{1}->key = 'PaymentDescriptionLanguageCode';
$kalixa->xml->specificPaymentData->data{1}->value = 'en';

$kalixa->xml->paymentAccountID = $user['paymentAccountID'];
unset($kalixa->xml->userData);
unset($kalixa->xml->paymentAccount);
$response = $kalixa->getResponse();

if (isset($response->payment->paymentID) && isset($response->payment->state->id)) {
  $order = Order::getOrderById($merchantTransactionID);
  $order->paymentID = $response->payment->paymentID;
  $order->state_id = $response->payment->state->id;
  $order->state = $response->payment->state->definition->value;
  DB::me()->commit();
} else {
  DB::me()->rollBack();
  echo '=(';
}

echo '<b>Request:</b> (' . $kalixa->getUrl() . ')';
// dump($kalixa->xml);
echo '<b>Response:</b>';
dump($response);