<?php
namespace app\models;

use Illuminate\Database\Eloquent\Model;
use app\core\App;
use app\models\Payment_account;
use app\models\Payment_method;

class Order extends Model{
    protected $guarded = ['id'];


    const STATE_PENDING_NUM = 105;
    const STATE_CENCELED_NUM = 1;
    const STATE_CAPTURED_NUM = 2;
  
    const STATE_PENDING = 'PendingToBeCaptured';
    const STATE_CENCELED = 'Cancelled';
    const STATE_CAPTURED = 'CapturedByProvider';
    const STATE_AUTHORISED = 'AuthorisedByProvider';
  
  
    public function actions($path = '/payment/') {
        ${self::STATE_CENCELED} = [];
        ${self::STATE_PENDING} = [
          'captured' => App::url($path . 'action/' . $this->paymentID . '/' . $this->id . '/' . self::STATE_CAPTURED_NUM),
          'refunded' => App::url($path . 'refunded/' . $this->id),
        ];
        ${self::STATE_CAPTURED} = [];
        ${self::STATE_AUTHORISED} = [
          'pending' => App::url($path . 'action/' . $this->paymentID . '/' . $this->id . '/' . self::STATE_PENDING_NUM),
          'cencel' => App::url($path . 'action/' . $this->paymentID . '/' . $this->id . '/' . self::STATE_CENCELED_NUM), 
        ];
    
        if (empty($this->state)) {
          throw new \Exception('Please specify #"state"');
        }
        ${$this->state}['info'] = App::url($path . 'info/' . $this->id);
        return ${$this->state};
    }
    
    public function user()
    {
        return $this->hasOne('\app\models\User', 'userID', 'user_id');
    }

    public function method()
    {
        return $this->hasOne('\app\models\Payment_method', 'id', 'method_id');
    }

    public static function setDeposit($user, $kalixa, $data)
    {
        $order = new Order;
        if (empty($data['paymentAccountID'])) {
            $Payment_method = Payment_method::find($data['paymentMethodID']);
        } else {
            $Payment_account = Payment_account::find($data['paymentAccountID']);            
            $Payment_method = Payment_method::find($Payment_account->method_id);
        }
        

        $order->user_id = $user->userID;
        $order->count_ven = $data['ven'];
        $order->method_id = $Payment_method->id;
        $order->save();

        $kalixa->xml->merchantTransactionID = $order->id;
        $kalixa->xml->merchantID = merchantID;
        $kalixa->xml->shopID = shopID;
        $kalixa->xml->amount = venToUsd($data['ven']);
        
        $kalixa->xml->userID = $user->userID;
        $kalixa->xml->userIP = $_SERVER['REMOTE_ADDR'];
        $kalixa->xml->userSessionID = \session_id();

        if (empty($data['paymentAccountID'])) {
            $kalixa->xml->paymentMethodID = $Payment_method->num; // 1 - ECMC Deposit, 2 - VISA Deposit, 73 - Maestro Deposit
            $kalixa->xml->creationTypeID = $Payment_method->creationTypeID; // 1 - Maestro Deposit, 3 - VISA Deposit (repeat)
            $kalixa->setUserData($user);
            $kalixa->setPaymentAccount($data);
        } else {
            $kalixa->xml->creationTypeID = $Payment_method->creationTypeID_repeated;
            $kalixa->xml->paymentMethodID = $Payment_method->num_repeated;
            $kalixa->xml->paymentAccountID = $Payment_account->payment_id;
            unset($kalixa->xml->userData);
            unset($kalixa->xml->paymentAccount);                
        }

        $kalixa->xml->specificPaymentData->data{0}->key = 'PaymentDescription';
        $kalixa->xml->specificPaymentData->data{0}->value = 'some description';
        $kalixa->xml->specificPaymentData->data{1}->key = 'PaymentDescriptionLanguageCode';
        $kalixa->xml->specificPaymentData->data{1}->value = 'en';
        if (73 == $kalixa->xml->paymentMethodID) {
            $kalixa->xml->specificPaymentData->data{2}->key = 'IsThreeDSecureRequired';
            $kalixa->xml->specificPaymentData->data{2}->value = 'true';
            // $kalixa->xml->specificPaymentData->data{3}->key = 'ShouldAllow3DSFallback';
            // $kalixa->xml->specificPaymentData->data{3}->value = 'true';  
        } else {
            unset($kalixa->xml->specificPaymentData->data{2});
            // unset($kalixa->xml->specificPaymentData->data{3});
        }
        // dump($kalixa->xml);
        // exit;
        $response = $kalixa->getResponse();
        if (isset($response->payment->paymentID) && isset($response->payment->state->id)) {
            $order->paymentID = $response->payment->paymentID;
            $order->state_id = $response->payment->state->id;
            $order->state = $response->payment->state->definition->value;
            $order->save();
            Payment_account::firstOrCreate([
                'payment_id' => $response->payment->paymentAccount->paymentAccountID, 
                'user_id' => $user->userID,
                'method_id' => $Payment_method->id,
            ]);
        } else {
            $order->delete();
        }
        return $response;
    }
}