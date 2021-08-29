<?php

namespace app\Core;
use app\Core\View;

class Controller
{
    public string $layout = 'main';
    public string $action = '';

    //Установка шаблона представления по умолчанию для контроллера
    public function setLayout(string $layout)
    {
        $this->layout = $layout;
    }

    //Метод для рендера представления. Передает представления и возможные параметры в сущность представление приложения
    public function render($view, $params = [])
    {
        return Application::$app->view->resolveView($view, $params);
    }
}

?>