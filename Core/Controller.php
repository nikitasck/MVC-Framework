<?php

namespace app\Core;
use app\Core\View;
use app\Core\middleware\BaseMiddleware;

class Controller
{
    public string $layout = 'main';
    public string $action = '';
    protected array $middlewares = []; 

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

    //Исползуем для задания допустимого middleware в дочернем контролеле. Для добавления нового посредника нужно создавать нвоый обьект и устанавливать нужный action.
    public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}

?>