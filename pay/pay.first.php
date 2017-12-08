<?php
require_once('sys/init.php');
use \lib\kalixa\Kalixa;
use \lib\kalixa\Order;
use \lib\DB;

if (empty($_POST['ven']) || !is_numeric($_POST['ven'])) {
  header('Location: ./');
  exit;
}
if (empty($_POST['paymentMethodID']) || !is_numeric($_POST['paymentMethodID'])) {
  header('Location: ./');
  exit;
}
if (!isset($_POST['pays'])) {
  header('Location: ./');
  exit;
}
$paymentMethodID = (int) $_POST['paymentMethodID'];
$payVen = (int) $_POST['ven'];

$number = (int) implode('', $_POST['card-number']);
$holder = filter_var($_POST['card-holder'], FILTER_SANITIZE_STRING);
$expiration_month = (int) $_POST['card-expiration-month'];
$expiration_year =  (int) $_POST['card-expiration-year'];
$ccv =  (int) $_POST['card-ccv'];

if ($number && $holder && $expiration_month && $expiration_year && $ccv) {
  $paymentAccount = [
    'CardNumber' => $number,
    'CardVerificationCode' => $ccv,
    'HolderName' => $holder,
    'ExpiryMonth' => $expiration_month,
    'ExpiryYear' => $expiration_year,
  ];

  DB::me()->beginTransaction();
  $merchantTransactionID = Order::create($user['userID'], $payVen);

  $kalixa = new Kalixa('initiatePayment.1');
  $kalixa->xml->merchantTransactionID = $merchantTransactionID;
  $kalixa->xml->merchantID = merchantID;
  $kalixa->xml->shopID = shopID;
  $kalixa->xml->amount = venToUsd($payVen);
  $kalixa->xml->paymentMethodID = $paymentMethodID; // 1 - ECMC Deposit, 2 - VISA Deposit, 73 - Maestro Deposit

  $kalixa->xml->userID = $user['userID'];

  $blackKeys = ['userID', 'userSessionID', 'creationTypeID', 'userIP', 'balance', 'paymentAccountID'];
  // $kalixa->xml->addChild('userData');
  foreach ($user as $key => $value) {
    if (in_array($key, $blackKeys)) {
      continue;
    }
    if (!is_array($value)) {
      $kalixa->xml->userData->$key = $value;
    } else {
      foreach ($value as $k => $v) {
        $kalixa->xml->userData->$key->$k = $v;
      }
    }
  }

  $kalixa->xml->userIP = $_SERVER['REMOTE_ADDR'];
  $kalixa->xml->userSessionID = $user['userSessionID'];
  $kalixa->xml->creationTypeID = $user['creationTypeID'];

  $kalixa->xml->specificPaymentData->data{0}->key = 'PaymentDescription';
  $kalixa->xml->specificPaymentData->data{0}->value = 'some description';
  $kalixa->xml->specificPaymentData->data{1}->key = 'PaymentDescriptionLanguageCode';
  $kalixa->xml->specificPaymentData->data{1}->value = 'en';

  $i = 0;
  foreach ($paymentAccount as $key => $value) {
    $kalixa->xml->paymentAccount->specificPaymentAccountData->data{$i}->key = $key;
    $kalixa->xml->paymentAccount->specificPaymentAccountData->data{$i}->value = $value;
    $i++;
  }

  //dump($kalixa->xml);
  // exit;
  $response = $kalixa->getResponse();

  if (isset($response->payment->paymentID) && isset($response->payment->state->id)) {

    $order = Order::getOrderById($merchantTransactionID);
    $order->paymentID = $response->payment->paymentID;
    $order->state_id = $response->payment->state->id;
    $order->state = $response->payment->state->definition->value;

    $q = DB::me()->prepare("UPDATE `users` SET `paymentAccountID` = ? WHERE `userID` = ? LIMIT 1");
    $q->execute([$response->payment->paymentAccount->paymentAccountID, $user['userID']]);

    DB::me()->commit();
  } else {
    DB::me()->rollBack();
    echo '<b>Ошибка запроса!</b>';
  }
  dump($response);

}