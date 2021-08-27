<?php

namespace app\Core;

use app\Core\Request;
use app\Core\Response;
use app\Core\Application;

/*

Класс маршрутизации. 
Для взаимодействия с ним использовать Router->get('route', [ControllerNmae:class, methodName]);
Для работы с отправкой или получением данных использовать методы get, post
Принимает в себя массив из контроллера и имя выполняемого метода. 
Получение данных, например, для передачи данных осуществляется с помощью Обьекта запроса(request).

*/

class Router
{
    private Request $request;
    private Response $response;
    private Array $routes;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    //Router->get('route', [ControllerNmae:class, methodName]);
    public function get(String $path, Array $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    //Router->post('route', [ControllerNmae:class, methodName]);
    public function post(String $path, Array $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    /*
    Метод для совершения маршрутизации

    Принимает в себя метод и путь от обьекта Request.

    Далее, проверяется, есть ли существующий путь, если нет, то передается исключение.
    Потом, проверяется, является ли callback функция строко или массивом. Если строка, то вызвыается метод renderView. Если массив, то создается обьект контроллера и выполняется его метод.

    Возвращаться должна call_user_func
    */
    public function resolve()
    {
        $method = $this->request->getMethod;
        $path = $this->request->getUrl;

        $callback = $this->routes[$path][$method] ?? false;

        if($callback === false) {
            //throw new routeException();
        }

        //Если передали просто строку, то это ссылка на представление
        if(is_string($callback)) {
            //Application::$app->view->renderView($callback);
        }

         //создаем сущность. Передаем в контроллер приложения эту сущность  
        if(is_array($callback)){
            $controller = new $callback[0]();
            
            //Application::$app->controller = $controller;//Передаем созданную сущность приложению.
            $controller->action = $callback[1]; //Используем метод Контроллера для получения дайствия.
            $callback[0] = $controller; //Передаю сущность контроллера

            //Тут можно реализовать выполнения всех middleware
        }

        // Вызываем выполнения controller->action с параметрами request и response. -------Не ясно, а как используются параметры request и response
        return call_user_func($callback, $this->request, $this->response);
    }
}

?>