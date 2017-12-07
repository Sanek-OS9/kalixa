<?php
/*
require_once('sys/init.php');

if (!isset($_GET['ven'])) {
  header('Location: ./');
  exit;  
}
$payVen = $_GET['ven'];
if (empty($_POST['paymentID']) || empty($_POST['merchantTransactionID'])) {
  header('Location: ./pay.form.php?ven=' . $countVen);
  exit;
}
$paymentID = $_POST['paymentID'];
$merchantTransactionID = $_POST['merchantTransactionID'];

$order_id = Order::create($user['userID'], $payVen);


$kalixa = new Kalixa('xml/initiatePaymentFromReference');
$kalixa->xml->merchantID = merchantID;
$kalixa->xml->shopID = shopID;
$kalixa->xml->originalPaymentID = $paymentID;
$kalixa->xml->merchantTransactionID = $merchantTransactionID;
$kalixa->xml->paymentMethodID = 2;
$kalixa->xml->amount = venToUsd($payVen);

$response = $kalixa->getResponse();

if (isset($response->payment->paymentID) && isset($response->payment->state->id)) {
  $order = new Order($order_id);
  $order->paymentID = $response->payment->paymentID;
  $order->state_id = $response->payment->state->id;
  $order->merchantTransactionID = $response->payment->merchantTransactionID;

  //echo '=)';
  //header('Refresh: 1; ./');
  //exit;
} else {
  echo '=(';
}

echo '<b>Request:</b> (' . $kalixa->getUrl() . ')';
dump($kalixa->xml);
echo '<b>Response:</b>';
dump($response);
*/