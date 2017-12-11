<?php
namespace app\http\controllers;

use app\core\Controller;
use app\models\{Payment_method, User};
use Valitron\Validator;
use lib\kalixa\{Kalixa, Order};

class PayController extends Controller{
    public function index()
    {
        $payments = Payment_method::all();
        $this->params['payments'] = $payments;
        $this->display('pay/form.pay');
    }
    public function send()
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
        $user = User::first();
        $kalixa = new Kalixa('initiatePayment.1');
        $kalixa->xml->merchantTransactionID = mt_rand(1111, 9999);
        $kalixa->xml->merchantID = merchantID;
        $kalixa->xml->shopID = shopID;
        $kalixa->xml->amount = venToUsd($_POST['ven']);
        $kalixa->xml->paymentMethodID = $_POST['paymentMethodID']; // 1 - ECMC Deposit, 2 - VISA Deposit, 73 - Maestro Deposit
      
        $kalixa->xml->userID = $user->userID;

        $kalixa->xml->userData->username = $user->username;
        $kalixa->xml->userData->firstname = $user->firstname;
        $kalixa->xml->userData->lastname = $user->lastname;
        $kalixa->xml->userData->currencyCode = $user->currencyCode;
        $kalixa->xml->userData->languageCode = $user->languageCode;
        $kalixa->xml->userData->email = $user->email;
        $kalixa->xml->userData->dateOfBirth = $user->dateOfBirth;
        $kalixa->xml->userData->gender = $user->gender;

        $kalixa->xml->userData->address->street = $user->address->street;
        $kalixa->xml->userData->address->houseNumber = $user->address->houseNumber;
        $kalixa->xml->userData->address->postalCode = $user->address->postalCode;
        $kalixa->xml->userData->address->city = $user->address->city;
        $kalixa->xml->userData->address->countryCode2 = $user->address->countryCode2;
        $kalixa->xml->userData->address->telephoneNumber = $user->address->telephoneNumber;
      
        $kalixa->xml->userIP = $_SERVER['REMOTE_ADDR'];
        $kalixa->xml->userSessionID = $user->userSessionID;
        $kalixa->xml->creationTypeID = $user->creationTypeID;
      
        $kalixa->xml->specificPaymentData->data{0}->key = 'PaymentDescription';
        $kalixa->xml->specificPaymentData->data{0}->value = 'some description';
        $kalixa->xml->specificPaymentData->data{1}->key = 'PaymentDescriptionLanguageCode';
        $kalixa->xml->specificPaymentData->data{1}->value = 'en';
      
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
        
        dump($response);
        $this->redirect('/orders');
    }
}