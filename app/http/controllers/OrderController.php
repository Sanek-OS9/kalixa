<?php
namespace app\http\controllers;

use app\core\Controller;
use app\models\{Order,User};

class OrderController extends Controller{
    public function all()
    {
        $user = User::firstOrNew(array('username' => 'Джон'));
        dump($user);
        echo $user->userID;
        $this->params['ank'] = User::find(1);
        $this->display('order');
    }
    public function delete(int $order_id)
    {
        if ($order = Order::find($order_id)){
            $order->delete();
        }
        $this->redirect('/orders');
    }
}