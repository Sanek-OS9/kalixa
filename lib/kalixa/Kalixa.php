<?php
namespace lib\kalixa;

use Valitron\Validator;

class Kalixa extends Kalixa_connect{
  protected $xml_file_path;
  public $xml;
  public $card_number;

  public function __construct($xml_file_path)
  {
    $this->xml_file_path = $xml_file_path;
    $this->xml = $this->loadXml();
        
  }

  public function __set($k, $v)
  {
    switch ($k) {
      case 'amount' : $this->xml->redirectParameters->grossAmount = $v;
      break;
    }
  }
  protected function loadXml()
  {
    $file_path = H . '/resources/xml/' . $this->xml_file_path . '.xml';
    if (!file_exists($file_path)) {
      throw new \Exception('File not exists #:' . $file_path);
    }
    return new \SimpleXMLElement(file_get_contents($file_path));
  }

  private function errorsShow(array $errors)
  {
    foreach ($errors as $error) {
      for ($i = 0; $i < count($error); $i++) {
        echo "{$error[$i]}<br>\r\n";
      }
    }
  }

  public function setUserData($user)
  {
    $v = new Validator($user);
    $v->rule('required', ['username', 'firstname', 'lastname', 'currencyCode', 'languageCode', 'email', 'dateOfBirth', 'gender']);
    $v->rule('email', 'email');
    $v->rule('array', 'user.address');
    $v->rule('required', ['address.street', 'address.houseNumber', 'address.postalCode', 'address.city', 'address.countryCode2', 'address.telephoneNumber']);

    if(!$v->validate()) {
      throw new \Exception('Error #:' . $this->errorsShow($v->errors()));
    }

    $this->xml->userData->username = $user->username;
    $this->xml->userData->firstname = $user->firstname;
    $this->xml->userData->lastname = $user->lastname;
    $this->xml->userData->currencyCode = $user->currencyCode;
    $this->xml->userData->languageCode = $user->languageCode;
    $this->xml->userData->email = $user->email;
    $this->xml->userData->dateOfBirth = $user->dateOfBirth;
    $this->xml->userData->gender = $user->gender;

    $this->xml->userData->address->street = $user->address->street;
    $this->xml->userData->address->houseNumber = $user->address->houseNumber;
    $this->xml->userData->address->postalCode = $user->address->postalCode;
    $this->xml->userData->address->city = $user->address->city;
    $this->xml->userData->address->countryCode2 = $user->address->countryCode2;
    $this->xml->userData->address->telephoneNumber = $user->address->telephoneNumber;
  }

  public function setPaymentAccount($card)
  {
    $v = new Validator($card);
    $v->rule('required', ['card-number', 'card-holder', 'card-expiration-month', 'card-expiration-year', 'card-ccv', 'card-ccv', 'card-number.*']);
    $v->rule('integer', ['card-expiration-year', 'card-ccv']);
    $v->rule('numeric', 'card-expiration-month');
    $v->rule('array', 'card-number');

    $card_number = implode('', $_POST['card-number']);
    $v_credit = new Validator(['card-number' => $card_number]);
    $v_credit->rule('creditCard', 'card-number');    
    if(!$v->validate() && !$v_credit->validate()) {
      throw new \Exception('Error #:' . $this->errorsShow($v->errors()));
      throw new \Exception('Error #:' . $this->errorsShow($v_credit->errors()));
    }

    $this->xml->paymentAccount->specificPaymentAccountData->data{0}->key = 'CardNumber';
    $this->xml->paymentAccount->specificPaymentAccountData->data{0}->value = $card_number;
    $this->xml->paymentAccount->specificPaymentAccountData->data{1}->key = 'CardVerificationCode';
    $this->xml->paymentAccount->specificPaymentAccountData->data{1}->value = $card['card-ccv'];
    $this->xml->paymentAccount->specificPaymentAccountData->data{2}->key = 'HolderName';
    $this->xml->paymentAccount->specificPaymentAccountData->data{2}->value = $card['card-holder'];
    $this->xml->paymentAccount->specificPaymentAccountData->data{3}->key = 'ExpiryMonth';
    $this->xml->paymentAccount->specificPaymentAccountData->data{3}->value = $card['card-expiration-month'];
    $this->xml->paymentAccount->specificPaymentAccountData->data{4}->key = 'ExpiryYear';
    $this->xml->paymentAccount->specificPaymentAccountData->data{4}->value = $card['card-expiration-year'];
  }

  public function getResponse()
  {
    if ($response = $this->request()) {
      return new \SimpleXMLElement($response);
    }
    throw new \Exception('Bad request #:' . $response);
  }
}
