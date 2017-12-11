<?php
namespace lib\kalixa;

class Kalixa extends Kalixa_connect{
  protected $xml_file_path;
  public $xml;

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

  public function getResponse()
  {
    if ($response = $this->request()) {
      return new \SimpleXMLElement($response);
    }
    throw new \Exception('Bad request #:' . $response);
  }
}
