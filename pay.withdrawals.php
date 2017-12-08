<?php
require_once('sys/init.php');
use \lib\kalixa\Kalixa;
use \lib\kalixa\Order;

if (empty($_GET['ven']) || empty($_GET['merchantTransactionID'])) {
  header('Location: ./');
  exit;  
}
$payVen = $_GET['ven'];
$merchantTransactionID = $_GET['merchantTransactionID'];

//echo $order_id;
$kalixa = new Kalixa('xml/initiatePayment');
$kalixa->xml->merchantID = merchantID;
$kalixa->xml->shopID = shopID;
$kalixa->xml->merchantTransactionID = $merchantTransactionID;
$kalixa->xml->paymentMethodID = 2;
$kalixa->xml->amount = venToUsd($payVen);
$kalixa->xml->userID = $user['userID'];

$kalixa->xml->userIP = $_SERVER['REMOTE_ADDR'];
$kalixa->xml->userSessionID = 123;
$kalixa->xml->creationTypeID = 3;
/*
$kalixa->xml->specificPaymentData->data{0}->key = 'PaymentDescription';
$kalixa->xml->specificPaymentData->data{0}->value = 'some description';
$kalixa->xml->specificPaymentData->data{1}->key = 'PaymentDescriptionLanguageCode';
$kalixa->xml->specificPaymentData->data{1}->value = 'en';
*/
$kalixa->xml->paymentAccountID = $user['paymentAccountID'];
//unset($kalixa->xml->userData);
// unset($kalixa->xml->paymentAccount);
//$kalixa->xml->userData = '';
/*
$paymentAccount = [
  'CardNumber' => 4111111111111111,
  'CardVerificationCode' => 111,
  'HolderName' => 'Ciquar Smith',
  'ExpiryMonth' => '01',
  'ExpiryYear' => 2020,
];

$i = 0;
foreach ($paymentAccount as $key => $value) {
  $kalixa->xml->paymentAccount->specificPaymentAccountData->data->key = $key;
  $kalixa->xml->paymentAccount->specificPaymentAccountData->data->value = $value;
  $i++;
}
*/
dump($kalixa->xml);
// exit;
$response = $kalixa->getResponse();

if (isset($response->payment->paymentID) && isset($response->payment->state->id)) {
  $order = new Order($order_id);
  $order->paymentID = $response->payment->paymentID;
  $order->state_id = $response->payment->state->id;
  $order->merchantTransactionID = $response->payment->merchantTransactionID;
  $order->state = $response->payment->state->definition->value;
  //echo '=)';
  //header('Refresh: 1; ./');
  //exit;
} else {
  //echo '=(';
}

echo '<b>Request:</b> (' . $kalixa->getUrl() . ')';
// dump($kalixa->xml);
echo '<b>Response:</b>';
dump($response);