<?php
namespace App\Core;

use app\core\App;

class Controller{
    protected $params = [];
    protected $template_dir = 'default';

    protected function access_denied($msg)
    {
        $this->params['message'] = $msg;
        $this->display('access_denied');
        exit;
    }
    protected function display($filename)
    {
        //$this->_inicialization();

        $this->params['server_name'] = $_SERVER['SERVER_NAME'];

        $loader = new \Twig_Loader_Filesystem(H . '/resources/views/' . $this->template_dir);
        $twig = new \Twig_Environment($loader);

        // $twig->addFunction(new \Twig_Function('__', function (string $string, string $param1 = '', string $param2 = '') {
        //   return __($string, $param1, $param2);
        // }));
        $twig->addFunction(new \Twig_SimpleFunction('url', function ($url) {
          return App::url($url);
        }));
        $template = $twig->loadTemplate('/' . $filename . '.twig');
        echo $template->render($this->params);
    }
    private function _inicialization()
    {

        $this->params['uri'] = str_replace(App::language()->getKeys(), '', App::getURI());
        $this->params['projects'] = Projects::getAll();
        $this->params['user'] = App::user();
        $this->params['language'] = App::language();
        $this->params['users'] = Users::getAll();
        $this->params['current_data'] = date('Y-m-d\TH:00');
        if (empty($this->params['id_activePproject']))
            $this->params['id_activePproject'] = 0;

        if (App::user()->id) {
            $form = new Form('/project/new/');
            $form->html('<span id="typeProject"></span>', false);
            $form->input(['name' => 'token', 'value' => App::user()->url_token, 'type' => 'hidden', 'br' => false]);
            $form->input(['name' => 'color', 'value' => 'red', 'type' => 'hidden', 'br' => false]);
            $form->input(['name' => 'title', 'holder' => __('Введите название')]);
            $form->submit(['name' => 'add', 'value' => __('Добавить'), 'br' => false]);
            $form->submit(['name' => 'cancel', 'value' => __('Отмена'), 'class' => 'cancel']);
            $this->params['form_project'] = $form->display();

            if (isset($this->params['add_task'])) {
                $form = new Form('/task/new/');
                $form->class = 'form-task';
                $form->id = 'formTask';
                $form->html('<span id="typeTask"></span>', false);
                $form->input(['name' => 'token', 'value' => App::user()->url_token, 'type' => 'hidden', 'br' => false]);
                $form->input(['name' => 'color', 'type' => 'hidden', 'value' => 'red', 'br' => false]);
                $form->input(['name' => 'message', 'holder' => __('Что вам нужно сделать?'), 'br' => false]);
                $form->input(['name' => 'deadlines', 'type' => 'datetime-local', 'value' => date('Y-m-d\TH:00'), 'br' => false]);
                $options = [];
                $projects = Projects::getAll();
                foreach ($projects AS $project) {
                    $options[] = ['value' => $project->id, 'title' => $project->title, 'selected' => $this->params['id_activePproject'] == $project->id ? 'selected' : ''];
                }
                $form->select(['name' => 'id_project', 'options' => $options]);
                $form->submit(['name' => 'add', 'value' => __('Добавить'), 'br' => false]);
                $form->submit(['name' => 'cancel', 'value' => __('Отмена'), 'class' => 'cancel']);
                $this->params['form_task_new'] = $form->display();
            }
        }
    }
    # доступ только пользователю
    protected function access_user()
    {
        if (!App::user()->id) {
            $this->access_denied(__('Страница доступна только авторизированным пользователям'));
        }
    }
    # доступ только гостью
    protected function access_guest()
    {
        if (App::user()->id) {
            $this->access_denied(__('Страница доступна только гостью'));
        }
    }
    # доступ только по токену
    protected function checkToken()
    {
        if (!App::user()->checkToken()) {
            $this->access_denied(__('Не верный token'));
        }
    }

    protected function redirect($path = '/'){
        header('Location: ' . App::url($path));
        exit;
    }
}
