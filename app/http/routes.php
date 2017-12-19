<?php
use app\core\Router;

Router::get('/orders', 'order@all');
Router::get('/order/delete/([0-9]+)', 'order@delete');

Router::get('/pay', 'pay@index');
Router::post('/pay/send', 'pay@send');

Router::get('/payment/info/([0-9]+)', 'order@info');
Router::get('/payment/action/([a-z0-9\-]+)/([0-9]+)/([0-9]+)', 'pay@action');
Router::get('/payment/refunded/([0-9]+)', 'order@refunded');
Router::post('/payment/refunded/([0-9]+)/send', 'order@refundedsend');