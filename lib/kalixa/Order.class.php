<?php
namespace lib\kalixa;

use lib\DB;

class Order{
  public $data;
  // public $id;
  public $table = 'orders';

  const STATE_PENDING_NUM = 105;
  const STATE_CENCELED_NUM = 1;
  const STATE_CAPTURED_NUM = 2;

  const STATE_PENDING = 'PendingToBeCaptured';
  const STATE_CENCELED = 'Cancelled';
  const STATE_CAPTURED = 'CapturedByProvider';
  const STATE_AUTHORISED = 'AuthorisedByProvider';

  private $is_update = false;

  public function __construct(array $order)
  {
    $this->data = $order;
  }

  public static function getOrderById($order_id)
  {
    $q = DB::me()->prepare("SELECT * FROM `orders` WHERE `id` = ? LIMIT 1");
    $q->execute([$order_id]);
    if ($order = $q->fetch()) {
      return new Order($order);
    }
    throw new \Exception('Order not found #' . $order_id);
  }

  public function actions($path = './') {
    ${self::STATE_CENCELED} = [];
    ${self::STATE_PENDING} = [
      'captured' => $path . 'payment.action.php?paymentID=' . $this->data['paymentID'] . '&amp;merchantTransactionID=' . $this->data['id'] . '&amp;action=' . self::STATE_CAPTURED_NUM,
      'refunded' => $path . 'payment.refunded.php?paymentID=' . $this->data['paymentID'] . '&amp;merchantTransactionID=' . $this->data['id'],
    ];
    ${self::STATE_CAPTURED} = [
      //'withdrawals' => $path . 'pay.withdrawals.php?paymentID=' . $this->data['paymentID'] . '&amp;merchantTransactionID=' . $this->data['id'] . '&amp;ven=15'
    ];
    ${self::STATE_AUTHORISED} = [
      'pending' => $path . 'payment.action.php?paymentID=' . $this->data['paymentID'] . '&amp;merchantTransactionID=' . $this->data['id'] . '&amp;action=' . self::STATE_PENDING_NUM,
      'cencel' => $path . 'payment.action.php?paymentID=' . $this->data['paymentID'] . '&amp;merchantTransactionID=' . $this->data['id'] . '&amp;action=' . self::STATE_CENCELED_NUM, 
      //'withdrawals' => $path . 'pay.withdrawals.php?paymentID=' . $this->data['paymentID'] . '&amp;merchantTransactionID=' . $this->data['id'] . '&amp;ven=15',
    ];

    if (empty($this->data['state'])) {
      throw new \Exception('Please specify #"state"');
    }
    ${$this->data['state']}['info'] = $path . 'payment.info.php?merchantTransactionID=' . $this->data['id'];

    return ${$this->data['state']};
  }
  public function getDate()
  {
    $date = new \DateTime();
    $date->setTimestamp($this->data['time']);
    return $date->format('Y-m-d H:i:s');
  }

  public function __get($name)
  {
    if (isset($this->data[$name])) {
      return $this->data[$name];
    }
    switch ($name){
      case 'date':
      return $this->getDate();
      default: return 'none';
    }
  }
  public function __set($name, $value)
  {
    if (isset($this->data[$name])) {
      $this->is_update = true;
      $this->data[$name] = $value;  
    } else {
      $this->$name = $value;
    }
    
  }

  public static function create($user_id, $payVen)
  {
    $q = DB::me()->prepare("INSERT INTO `orders` (`user_id`,`count_ven`,`time`) VALUES (?,?,?)");
    $q->execute([$user_id, $payVen, time()]);
    return DB::me()->lastInsertId();
  }


  public function __isset($name)
  {
    return isset($this->data[$name]);
  }

  private function update()
  {
    if (!$this->is_update) {
      return;
    }
    $q = DB::me()->prepare("UPDATE `orders` SET `user_id` = ?, `time` = ?, `paymentID` = ?, `state_id` = ?, `count_ven` = ?, `state` = ? WHERE `id` = ? LIMIT 1");
    $q->execute([$this->data['user_id'], $this->data['time'], $this->data['paymentID'], $this->data['state_id'], $this->data['count_ven'], $this->data['state'], $this->data['id']]);
  }

  public function delete()
  {
    $q = DB::me()->prepare("DELETE FROM `orders` WHERE `id` = ? LIMIT 1");
    $q->execute([$this->data['id']]);
    $this->data = [];
  }

  public function __destruct()
  {
    $this->update();
  }
}