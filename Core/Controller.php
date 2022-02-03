<?php

namespace app\Core;
use app\Core\View;
use app\Core\middleware\BaseMiddleware;

class Controller
{
    public string $layout = 'main';
    public string $action = '';
    protected array $middlewares = []; 

    //Set view layout.
    public function setLayout(string $layout)
    {
        $this->layout = $layout;
    }

    //Render view.
    public function render($view, $params = [])
    {
        return Application::$app->view->resolveView($view, $params);
    }

    //Register middleware
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