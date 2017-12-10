<?php
namespace app\http\controllers;

use app\core\Controller;
use app\models\Payment_method;
use Valitron\Validator;

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

        $v->rule('required', ['ven', 'paymentMethodID', 'paymentMethodID', 'card-number', 'card-holder', 'card-expiration-month', 'card-expiration-year', 'card-ccv', 'card-ccv']);
        $v->rule('integer', ['ven', 'paymentMethodID', 'card-expiration-year', 'card-ccv']);
        $v->rule('numeric', 'card-expiration-month');
        $v->rule('array', 'card-number');
        $v->rule('integer', 'card-number.*');
        $number = (int) implode('', $_POST['card-number']);

        $v_credit = new Validator(['card-number' => $number]);
        $v_credit->rule('creditCard', 'card-number');

        dump($_POST);

        if(!$v->validate() && !$v_credit->validate()) {
            dump($v->errors());
            dump($v_credit->errors());
            exit;
        }
        
        echo "Yay! We're all good!";
    }
}