<?php
namespace app\http\controllers;

use app\core\Controller;
use app\models\{Payment_method, User, Order};
use Valitron\Validator;
use lib\kalixa\{Kalixa};

class PayController extends Controller{
    public $user;

    public function index()
    {
        $payments = Payment_method::all();
        $this->params['payments'] = $payments;
        $this->display('pay/form.pay');
    }
    public function send()
    {
        $v = new Validator($_POST);
        $v->rule('required', 'ven');
        $v->rule('integer', 'ven');

        if(!$v->validate()) {
            dump($v->errors());
            exit;
        }
        $this->user = User::first();
        $this->order = new Order;
        $this->order->user_id = $this->user->userID;
        $this->order->count_ven = $_POST['ven'];
        $this->order->save();
        if ($this->user->paymentAccountID) {
            $this->payFirst();
            // $this->pay();
        } else {
            $this->payFirst();
        }
        
    }
    private function pay()
    {
        $kalixa = new Kalixa('initiatePayment.1');
        $kalixa->xml->merchantID = merchantID;
        $kalixa->xml->shopID = shopID;
        $kalixa->xml->merchantTransactionID = $this->order->id;
        $kalixa->xml->paymentMethodID = $_POST['paymentMethodID']; // 1 - ECMC Deposit, 2 - VISA Deposit, 73 - Maestro Deposit
        $kalixa->xml->amount = venToUsd($_POST['ven']);
        $kalixa->xml->userID = $this->user->userID;
        
        $kalixa->xml->userIP = $_SERVER['REMOTE_ADDR'];
        $kalixa->xml->userSessionID = 123;
        $kalixa->xml->creationTypeID = 3;
        
        $kalixa->xml->specificPaymentData->data{0}->key = 'PaymentDescription';
        $kalixa->xml->specificPaymentData->data{0}->value = 'some description';
        $kalixa->xml->specificPaymentData->data{1}->key = 'PaymentDescriptionLanguageCode';
        $kalixa->xml->specificPaymentData->data{1}->value = 'en';
        
        $kalixa->xml->paymentAccountID = $this->user->paymentAccountID;
        unset($kalixa->xml->userData);
        unset($kalixa->xml->paymentAccount);
        $response = $kalixa->getResponse();
        
        if (isset($response->payment->paymentID) && isset($response->payment->state->id)) {
            $this->order->paymentID = $response->payment->paymentID;
            $this->order->state_id = $response->payment->state->id;
            $this->order->state = $response->payment->state->definition->value;
            $this->order->save();
        } else {
            $this->order->delete();
        }
        
        echo '<b>Request:</b> (' . $kalixa->getUrl() . ')';
        // dump($kalixa->xml);
        echo '<b>Response:</b>';
        dump($response);
    }

    private function payFirst()
    {
        $v = new Validator($_POST);
        $v->rule('required', ['ven', 'paymentMethodID', 'card-number', 'card-holder', 'card-expiration-month', 'card-expiration-year', 'card-ccv', 'card-ccv', 'card-number.*']);
        $v->rule('integer', ['ven', 'paymentMethodID', 'card-expiration-year', 'card-ccv']);
        $v->rule('numeric', 'card-expiration-month');
        $v->rule('array', 'card-number');

        $card_number = (int) implode('', $_POST['card-number']);
        $v_credit = new Validator(['card-number' => $card_number]);
        $v_credit->rule('creditCard', 'card-number');

        //dump($_POST);

        if(!$v->validate() && !$v_credit->validate()) {
            dump($v->errors());
            dump($v_credit->errors());
            exit;
        }
        //$user = User::first();
        // $this->order = new Order;
        // $this->order->user_id = $this->user->userID;
        // $this->order->count_ven = $_POST['ven'];
        // $this->order->save();

        $kalixa = new Kalixa('initiatePayment.1');
        $kalixa->xml->merchantTransactionID = $this->order->id;
        $kalixa->xml->merchantID = merchantID;
        $kalixa->xml->shopID = shopID;
        $kalixa->xml->amount = venToUsd($_POST['ven']);
        $kalixa->xml->paymentMethodID = $_POST['paymentMethodID']; // 1 - ECMC Deposit, 2 - VISA Deposit, 73 - Maestro Deposit
      
        $kalixa->xml->userID = $this->user->userID;

        $kalixa->xml->userData->username = $this->user->username;
        $kalixa->xml->userData->firstname = $this->user->firstname;
        $kalixa->xml->userData->lastname = $this->user->lastname;
        $kalixa->xml->userData->currencyCode = $this->user->currencyCode;
        $kalixa->xml->userData->languageCode = $this->user->languageCode;
        $kalixa->xml->userData->email = $this->user->email;
        $kalixa->xml->userData->dateOfBirth = $this->user->dateOfBirth;
        $kalixa->xml->userData->gender = $this->user->gender;

        $kalixa->xml->userData->address->street = $this->user->address->street;
        $kalixa->xml->userData->address->houseNumber = $this->user->address->houseNumber;
        $kalixa->xml->userData->address->postalCode = $this->user->address->postalCode;
        $kalixa->xml->userData->address->city = $this->user->address->city;
        $kalixa->xml->userData->address->countryCode2 = $this->user->address->countryCode2;
        $kalixa->xml->userData->address->telephoneNumber = $this->user->address->telephoneNumber;
      
        $kalixa->xml->userIP = $_SERVER['REMOTE_ADDR'];
        $kalixa->xml->userSessionID = $this->user->userSessionID;
        $kalixa->xml->creationTypeID = $this->user->creationTypeID;
      
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
            unset($kalixa->xml->specificPaymentData->data{3});
        }
              
        $kalixa->xml->paymentAccount->specificPaymentAccountData->data{0}->key = 'CardNumber';
        $kalixa->xml->paymentAccount->specificPaymentAccountData->data{0}->value = $card_number;
        $kalixa->xml->paymentAccount->specificPaymentAccountData->data{1}->key = 'CardVerificationCode';
        $kalixa->xml->paymentAccount->specificPaymentAccountData->data{1}->value = $_POST['card-ccv'];
        $kalixa->xml->paymentAccount->specificPaymentAccountData->data{2}->key = 'HolderName';
        $kalixa->xml->paymentAccount->specificPaymentAccountData->data{2}->value = $_POST['card-holder'];
        $kalixa->xml->paymentAccount->specificPaymentAccountData->data{3}->key = 'ExpiryMonth';
        $kalixa->xml->paymentAccount->specificPaymentAccountData->data{3}->value = $_POST['card-expiration-month'];
        $kalixa->xml->paymentAccount->specificPaymentAccountData->data{4}->key = 'ExpiryYear';
        $kalixa->xml->paymentAccount->specificPaymentAccountData->data{4}->value = $_POST['card-expiration-year'];
     
        //dump($kalixa->xml);
        //exit;
        $response = $kalixa->getResponse();
        if (isset($response->payment->paymentID) && isset($response->payment->state->id)) {
            $this->order->paymentID = $response->payment->paymentID;
            $this->order->state_id = $response->payment->state->id;
            $this->order->state = $response->payment->state->definition->value;
            $this->order->save();

            $this->user->paymentAccountID = $response->payment->paymentAccount->paymentAccountID;
            $this->user->save();
        } else {
            $this->order->delete();
        }
        dump($response);
        //$this->redirect('/orders');
    }
}