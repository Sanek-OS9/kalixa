<?php
namespace app\http\controllers;

use app\core\{Controller, Captcha};
use app\models\User;

class MainController extends Controller{
    public function index()
    {
        $this->params['message'] = '=)))';
        $this->display('home');
    }

    public function captcha()
    {
        $captcha = new Captcha;
        echo $captcha->show();
    }
}