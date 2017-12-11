<?php
namespace lib\kalixa;

class Kalixa_connect{
  protected $ch;

  public function request()
  {
    $this->ch = curl_init();
    curl_setopt_array($this->ch, [
      CURLOPT_URL => $this->getUrl(),
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => $this->xml->asXML(),
      CURLOPT_HTTPHEADER => array(
        "authorization: Basic {$this->getHashAuthorization()}",
        "cache-control: no-cache",
        "content-type: application/xml",
      ),
    ]);
    $response = curl_exec($this->ch);
    if ($err = curl_error($this->ch)) {
      throw new \Exception('cURL Error #:' . $err);
    } else {
      return $response;
    }
  }

  public function getUrl()
  {
    $method = preg_replace('/([\W\d])/i', '', basename($this->xml_file_path));
    // echo $method;
    // exit;
    return 'https://api.test.kalixa.com/PaymentRedirectionService/PaymentService.svc/pox/' . $method;
  }

  public function getHashAuthorization()
  {
    return base64_encode(USER_LOGIN . ':' . USER_PASS);
  }

  public function __destruct()
  {
    if ($this->ch) {
      curl_close($this->ch);
    }    
  }
}