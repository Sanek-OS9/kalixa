<?php
namespace app\http\controllers;

use app\core\Controller;
use app\models\{Order,User};

class OrderController extends Controller{
    public function all()
    {
        $user = User::find(1999);
        $orders = Order::where('user_id', '=', $user->userID)->get();

        // foreach ($orders as $order) {
        //     dump($order->actions());
        //     exit;
        // }
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
}