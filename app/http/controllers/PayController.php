<?php
namespace app\http\controllers;

use app\core\Controller;
use app\models\Payment_method;
use app\models\User;
use app\models\Order;
use Valitron\Validator;
use lib\kalixa\Kalixa;

class PayController extends Controller{
    public $user;

    public function index()
    {
        $payments = Payment_method::all();
        $user = User::first();
        $this->params['payments'] = $payments;
        $this->params['user'] = $user;
        $this->display('pay/form.pay');
    }
    
    public function send()
    {
        $user = User::first();
        $kalixa = new Kalixa('initiatePayment.1');
        $response = Order::setDeposit($user, $kalixa, $_POST);

        dump($response);
        if (empty($response->payment->state->paymentStateDetails->detail[0]->value)) {
            return;
        }
        $kalixa = new Kalixa('executePaymentAction');
        $kalixa->xml->merchantID = merchantID;
        $kalixa->xml->shopID = shopID;
        $kalixa->xml->paymentID = $response->payment->paymentID;
        $kalixa->xml->actionID = 90;
        $kalixa->xml->actionData->data{0}->key = 'PaReq';
        $kalixa->xml->actionData->data{0}->value = $response->payment->state->paymentStateDetails->detail->{2}->value;
        $kalixa->xml->actionData->data{1}->key = 'MD';
        $kalixa->xml->actionData->data{1}->value = $response->payment->state->paymentStateDetails->detail->{3}->value;
        $kalixa->xml->actionData->data{2}->key = 'TermUrl';
        $kalixa->xml->actionData->data{2}->value = $response->payment->state->paymentStateDetails->detail->{1}->value;

        $response = $kalixa->getResponse();
        dump($kalixa->xml);
        dump($response);
        $this->params['payment'] = $response->payment;
        // $this->display('pay/ThreeDSecureListener');
    }

    public function action($paymentID, $merchantTransactionID, $action)
    {
        $remarks = [
            1 => 'Cancelled',
            105 => 'PendingToBeCaptured',
            2 => 'CapturedByProvider',
        ];
          
        $kalixa = new Kalixa('executePaymentAction');
        $kalixa->xml->merchantID = merchantID;
        $kalixa->xml->shopID = shopID;
        
        $kalixa->xml->paymentID = $paymentID;
        $kalixa->xml->actionID = $action;
        $kalixa->xml->remark = $remarks[$action] . " Payment TEST";
        unset($kalixa->xml->actionData);
        $response = $kalixa->getResponse();
        
        if (!isset($response->errorCode)) {
            $order = Order::find($merchantTransactionID);
            $order->state = $remarks[$action];
            $order->save();
        }
        
        echo '<b>Request:</b> (' . $kalixa->getUrl() . ')';
        dump($kalixa->xml);
        echo '<b>Response:</b>';
        dump($response);
    }
}