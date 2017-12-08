<?php
require_once('../sys/init.php');
use lib\DB;

$q = DB::me()->query("SELECT * FROM `payment_methods` WHERE `name` LIKE '%deposit%'");
$items = $q->fetchAll();
?>
Выберите способ оплаты <br>
<?php foreach ($items as $item): ?>
  <a href="./step1.php?paymentMethodID=<?= $item['num'] ?>"><img src="/public/images/payment_method/<?= $item['num'] ?>.png" width="300"/></a> 
<?php endforeach; ?>