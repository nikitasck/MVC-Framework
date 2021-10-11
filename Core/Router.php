<?php

namespace app\Core;

use app\Core\Request;
use app\Core\Response;
use app\Core\Application;
use app\Core\exception\NotFoundException;
use PDO;

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
    protected array $routes = [];
    public $params = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }



    //Adds to an $routes array passed data into 'get' key: 
    //Router->get('route', [ControllerNmae:class, methodName], ['{param}']);
    //Params for callback empty for default.If any params passed to the get method, it will be put in array with callback.
    public function get(String $path, $callback, $params = [])
    {
        
        if(count($params)) {
            $this->routes['get'][$path] = [$callback, $params];
        } else {
            $this->routes['get'][$path] = [$callback];
        }
        

        //$this->routes['get'][$path] = $callback;
    }

    //Adds to an $routes array passed data into 'get' key: 
    //Router->post('route', [ControllerNmae:class, methodName]);
    public function post(String $path, $callback, $param = '')
    {
        $this->routes['post'][$path] = [$callback];
    }

    //Возвращает маршрут отсортированный маршрут, который соотвествует заданному в routes[]. 
    //Получает маршрут от сервера и выбирает максимально схожий маршрут из роутера.
    public function filterRequestPath($reqPath, $method)
    {
        //Получение маршрутов из routes[] по требуемому методу.
        $test = array_keys($this->routes[$method]);

        //Перебираю маршруты
        foreach($test as $value) {

            //Если полученный маршрут с сервера существует в массиве routes(заданные маршруты Роутера)
            if(str_contains($reqPath, $value)){
                //Выбираем максимально приблеженный маршрут.
                //1 - если запрашиваемый маршрут длинее
                //0 - если маршруты совпадают
                if(strcmp($reqPath, $value)  >= 1){
                    $filterPath = $value;
                    //Есть ли в данном маршруте получаеммый параметр, если да, 
                    //то вызвать метод, который вырежит передаваемый параметр и запишет его в переменную.
                    if($this->isPathContainsParam($method, $value)){
                        $this->params['param'] = $this->takeParamFromRequestedPath($reqPath);
                        return $filterPath;
                    }
                } elseif(strcmp($reqPath, $value)  === 0)
                {
                    return $reqPath;
                }
            }
        }
    }

    //Если маршрут содержит параметр, то вернется истина
    public function isPathContainsParam($method, $path): bool
    {
        if(!empty($this->routes[$method][$path][1])){
            return true;
        }
        return false;
    }

    //Separates path with '/' and returns an array.
    public function pathToArray($path): Array
    {
        $pathArray = explode('/', $path);
        return $pathArray;
    }

    //Возвращает параметр из маршрута запроса
    //Переделать, чтобы сравнивались строки и выбиралось последнее значение
    public function takeParamFromRequestedPath($path)
    {
        $arr = $this->pathToArray($path);
        return array_pop($arr);
    }

    public function resolve()
    {
        $method = $this->request->getMethod();
        $path = $this->filterRequestPath($this->request->getUrl(), $method);

        $callbackData = $this->routes[$method][$path] ?? false;

        if(is_array($callbackData)) {
            $callback = $callbackData[0];

            if($callback === false) {
                throw new NotFoundException();
            }

                //Если передали просто строку, то это ссылка на представление
            if(is_string($callback)) {
                return Application::$app->view->inputContent($callback);
            }

            if(is_array($callback)){
                $controller = new $callback[0]();
                
                Application::$app->controller = $controller;//Передаем созданную сущность приложению.
                $controller->action = $callback[1]; //Используем метод Контроллера для получения дайствия.
                $callback[0] = $controller; //Передаю сущность контроллера
        
                //Тут реализовать выполнения всех middleware
                foreach($controller->getMiddlewares() as $middleware) {
                    $middleware->execute();
                }
            }

            return call_user_func($callback, $this->request, $this->response, $this->params);

        } else {
            //Сделать вывод ошибки
            throw new NotFoundException();
        }
    }
}

?>