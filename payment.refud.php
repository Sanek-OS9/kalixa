<?php
require_once('sys/init.php');

echo '<a href="/orders.php"><<< orders</a><br><br>';

if (empty($_GET['paymentID']) || empty($_GET['merchantTransactionID'])) {
  header('Location: ./orders.php');
  exit;
}
$paymentID = $_GET['paymentID'];
$merchantTransactionID = (int) $_GET['merchantTransactionID'];

if (isset($_POST['refud'])) {
  $count = (int) $_POST['count'];

  $kalixa = new Kalixa('xml/initiatePaymentFromReference');
  $kalixa->xml->merchantID = merchantID;
  $kalixa->xml->shopID = shopID;

  $kalixa->xml->originalPaymentID = $paymentID;
  $kalixa->xml->merchantTransactionID = $merchantTransactionID;
  $kalixa->xml->paymentMethodID = 88;
  $kalixa->xml->amount = $count;

  $response = $kalixa->getResponse();

  echo '<b>Request:</b> (' . $kalixa->getUrl() . ')';
  dump($kalixa->xml);
  echo '<b>Response:</b>';
  dump($response);

}
?>
<form action="?paymentID=<?= $paymentID ?>&merchantTransactionID=<?= $merchantTransactionID ?>" method="POST">
  <input type="text" name="count">
  <input type="submit" name="refud" value="refud">
</form>