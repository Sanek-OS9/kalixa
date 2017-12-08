<?php
require_once('../sys/init.php');

if (!isset($_GET['paymentMethodID'])) {
  header('Location: ./');
  exit;
}
$paymentMethodID = (int) $_GET['paymentMethodID'];
?>
<form action="./step2.php" method="POST">
  <input type="hidden" name="paymentMethodID" value="<?= $paymentMethodID ?>"/>
  <input type="text" name="ven"/>
  <input type="submit" value="Pay">
</form>