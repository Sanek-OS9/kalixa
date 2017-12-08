<?php
/**
 * Класс для формирования HTML документа.
 */
namespace lib;

class Document
{
    public $title; // загловок страницы
    public $empty; // информационное сообщение
    public $listGroup; // ID пользователя которого нужно подсветить в шаблоне
    protected $err = []; // список уведомлений о ошибках
    protected $msg = []; // список уведомлений о успешных действиях
    protected $returns = []; // навигационный маршрут
    protected $actions = []; // действия
    protected $outputed = false; // метка о том что html код был показан
    protected $usersList = []; // список пользователей

    public function __construct()
    {
        $this->title = 'Тестовое задание' ; // название сайта
        ob_start();
    }
    /*
     * Формируем массив "хлебных-крошек"
     */
    public function ret($name, $url)
    {
        return $this->returns[$url] = $name ;
    }
    /*
     * Формируем массив действий
     */
    public function act($name, $url)
    {
        return $this->actions[$url] = $name ;
    }
    /*
     * Формируем массив уведомлений ошибок
     */
    public function err($text)
    {
        return $this->err[] = $text ;
    }
    /*
     * формируем массив уведомлений о успешном действии
     */
    public function msg($text)
    {
        return $this->msg[] = $text ;
    }
    /**
     * Формирование HTML документа и отправка данных браузеру
     */
    private function output()
    {
        if ($this->outputed) {
            // повторная отправка html кода вызовет нарушение синтаксиса документа, да и вообще нам этого нафиг не надо
            return;
        }
        $this->outputed = true;
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Pragma: no-cache');
        //header('Expires: ' . date('r'));
        header('X-UA-Compatible: IE=edge'); // отключение режима совместимости в осле
        header('Content-Type: text/html; charset=utf-8');
        $content =  ob_get_clean();
        $title = $this->title;
/*
        $loader = new \Twig_Loader_Filesystem(H . '/Views/' . THEME . '/templates/');
        $twig = new \Twig_Environment($loader);

        $template = $twig->loadTemplate('document.html');

        echo $template->render(
            [
                'content' => $content,
                'path' => $path,
                'user' => User(),
                'hash' => \Models\Guest::getHash(),
                'users' => $this->usersList,
                'title' => $this->title
            ]
        );
*/
        include_once $_SERVER['DOCUMENT_ROOT'] . '/resources/views/default/templates/document.tpl.php';
    }
    /**
     * Очистка вывода
     * Тема оформления применяться не будет
     */
    public function clean()
    {
        $this->outputed = true;
        ob_clean();
    }
    /**
     * То что срабатывает при exit
     */
    public function __destruct()
    {
        $this->output();
    }
}
