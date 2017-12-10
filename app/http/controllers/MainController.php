<?php
namespace app\http\controllers;

use app\core\Controller;
use app\models\User;

class MainController extends Controller{
    public function index()
    {
        $this->params['message'] = '=)))';
        $this->display('home');
    }
}