<?php
namespace app\http\controllers;

use app\core\Controller;
use app\models\Order;
use app\models\User;
use lib\kalixa\Kalixa;
use Valitron\Validator;

class OrderController extends Controller{

    public function all()
    {
        $user = User::first();
        $orders = Order::where('user_id', '=', $user->userID)->get();

        $this->params['orders'] = $orders;

        $this->display('order');
    }

    public function delete(int $order_id)
    {
        if ($order = Order::find($order_id)){
            $order->delete();
        }
        $this->redirect('/orders');
    }

    public function info($merchantTransactionID)
    {
        $order = Order::find($merchantTransactionID);

        $kalixa = new Kalixa('getPayments');
        $kalixa->xml->merchantID = merchantID;
        $kalixa->xml->shopID = shopID;
        $kalixa->xml->merchantTransactionID = $merchantTransactionID;

        $response = $kalixa->getResponse();

        if ($order->state != $kalixa->state) {
            $order->state = $kalixa->state;
            $order->save();
        }

        echo '<b>Request:</b> (' . $kalixa->getUrl() . ')';
        dump($kalixa->xml);
        echo '<b>Response:</b>';
        dump($response);
    }

    public function refundedsend($merchantTransactionID)
    {
        $v = new Validator($_POST);
        $v->rule('required', ['refud', 'count']);
        $v->rule('integer', 'count');
    
        if(!$v->validate()) {
          dump($v->errors());
          return;
        }
        $order = Order::find($merchantTransactionID);
        $kalixa = new Kalixa('initiatePaymentFromReference');
        $kalixa->xml->merchantID = merchantID;
        $kalixa->xml->shopID = shopID;
      
        $kalixa->xml->originalPaymentID = $order->paymentID;
        $kalixa->xml->merchantTransactionID = $order->id;
        $kalixa->xml->paymentMethodID = $order->method->refunded; // 89 - ECMC Deposit, 88 - VISA Deposit, 90 - Maestro Refund
        $kalixa->xml->amount = $_POST['count'];
      
        $response = $kalixa->getResponse();
      
        echo '<b>Request:</b> (' . $kalixa->getUrl() . ')';
        dump($kalixa->xml);
        echo '<b>Response:</b>';
        dump($response);

    }
    public function refunded($merchantTransactionID)
    {
        $order = Order::find($merchantTransactionID);
        $this->params['order'] = $order;
        $this->display('order/refunded');
    }
}