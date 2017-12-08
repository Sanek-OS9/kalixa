<?php
require_once('sys/init.php');

use lib\kalixa\Order;
use lib\DB;

echo '<a href="/"><<< home</a><br><br>';

if (isset($_GET['delete'])) {
  $merchantTransactionID = (int) $_GET['delete'];
  $order = new Order($merchantTransactionID);
  $order->delete();
  header('Location: ./orders.php');
  exit;
}
$q = DB::me()->query("SELECT * FROM `orders`");
$items = $q->fetchAll();

$i = 1;
foreach ($items as $item):
  $order = new Order($item);

  $order->actions();

  $ank = getUser($order->user_id);
  echo $i . '. <b>' . $ank['username'] . '</b> bought <em>' . $order->count_ven . '</em> Ven <small>(<b>' . $order->state . '</b>)</small> <small>(' . $order->date . ')</small>';
  foreach ($order->actions() as $name => $path) {
    echo ' [<a href="' . $path . '">' . $name . '</a>]';
  }
  /*
  echo ' [<a href="payment.info.php?merchantTransactionID=' . $order->id . '">info</a>]';
  // если платеж не отменен то можно выполнять действия
  if ('Cenceled' != $order->state) {
    // если статус PendingToBeCaptured то можно делать возврат (Refunded)
    if ('PendingToBeCaptured' == $order->state) {
      echo ' [<a href="payment.refud.php?paymentID=' . $order->paymentID . '&amp;merchantTransactionID=' . $order->id . '">refunded</a>]';
    }
    // если статус авторизован, можно менять статусы на Cenceled, PendingToBeCaptured и withdrawals
    if ('AuthorisedByProvider' == $order->state) {

      echo ' [<a href="payment.action.php?paymentID=' . $order->paymentID . '&amp;merchantTransactionID=' . $order->id . '&amp;action=' . $order::STATE_PENDING_NUM . '">PendingToBeCaptured</a>]';

      echo ' [<a href="payment.action.php?paymentID=' . $order->paymentID . '&amp;merchantTransactionID=' . $order->id . '&amp;action=' . $order::STATE_CENCELED_NUM . '">cencel</a>]';

      echo ' [<a href="pay.withdrawals.php?paymentID=' . $order->paymentID . '&amp;merchantTransactionID=' . $order->id . '&amp;ven=15">withdrawals</a>]';

      // если статус PendingToBeCaptured то можно изменить на CapturedByProvider
    } elseif ('PendingToBeCaptured' == $order->state) {
      echo ' [<a href="payment.action.php?paymentID=' . $order->paymentID . '&amp;merchantTransactionID=' . $order->id . '&amp;action=' . $order::STATE_CAPTURED_NUM . '">CapturedByProvider</a>]';

      // если статус CapturedByProvider то можно сменить на withdrawals
    } elseif ('CapturedByProvider' == $order->state) {

      echo ' [<a href="pay.withdrawals.php?paymentID=' . $order->paymentID . '&amp;merchantTransactionID=' . $order->id . '&amp;ven=15">withdrawals</a>]';
    }        
  }
  */
  echo ' [<a href="?delete=' . $order->id . '" style="color:red;">x</a>]';
  echo '<br>';
  $i++;
endforeach;