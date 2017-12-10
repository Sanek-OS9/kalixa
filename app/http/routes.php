<?php
use app\core\Router;

Router::get('/orders', 'order@all');
Router::get('/order/delete/([0-9]+)', 'order@delete');

Router::get('/pay', 'pay@index');
Router::post('/pay/send', 'pay@send');
