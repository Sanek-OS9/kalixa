<?php
require_once('sys/init.php');

if (empty($_GET['paymentID']) || empty($_GET['merchantTransactionID']) || empty($_GET['action'])) {
  header('Location: ./orders.php');
  exit;
}
$paymentID = $_GET['paymentID'];
$merchantTransactionID = (int) $_GET['merchantTransactionID'];
$action = (int) $_GET['action'];
$remarks = [
  1 => 'Cancelled',
  105 => 'PendingToBeCaptured',
  2 => 'CapturedByProvider',
];

$kalixa = new Kalixa('xml/executePaymentAction');
$kalixa->xml->merchantID = merchantID;
$kalixa->xml->shopID = shopID;

$kalixa->xml->paymentID = $paymentID;
$kalixa->xml->actionID = $action;
$kalixa->xml->remark = $remarks[$action] . " Payment TEST";

$response = $kalixa->getResponse();

if (!isset($response->errorCode)) {
  $order = new Order($merchantTransactionID);
  $order->state = $remarks[$action];
}

echo '<b>Request:</b> (' . $kalixa->getUrl() . ')';
dump($kalixa->xml);
echo '<b>Response:</b>';
dump($response);
