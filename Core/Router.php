<?php

namespace app\Core;

use app\Core\Request;
use app\Core\Response;
use app\Core\Application;
use app\Core\exception\NotFoundException;

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
    }

    //Adds to an $routes array passed data into 'get' key: 
    //Router->post('route', [ControllerNmae:class, methodName]);
    public function post(String $path, $callback, $params = [])
    {
        if(count($params)) {
            $this->routes['post'][$path] = [$callback, $params];
        } else {
            $this->routes['post'][$path] = [$callback];
        }
    }

    //Возвращает маршрут отсортированный маршрут, который соотвествует заданному в routes[]. 
    //Получает маршрут от сервера и выбирает максимально схожий маршрут из роутера.
    public function filterRequestPath($reqPath, $method)
    {
        //Retrieving routes with matched methods.
        $test = array_keys($this->routes[$method]);

        foreach($test as $value) {

            //If route existing at routes array
            if(str_contains($reqPath, $value)){
                //Choose the closest route.
                //1 - requested route is longer
                //0 - routes match
                if(strcmp($reqPath, $value)  >= 1){
                    $filterPath = $value;
                    //If route contains param, cut it and save in $params. Return path.
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

    //Check for existing param.
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

    //Retrieving array from exploded path.Returning last element from array.
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

            //If callback just string, put content in layout.
            if(is_string($callback)) {
                return Application::$app->view->inputContent($callback);
            }

            if(is_array($callback)){
                $controller = new $callback[0]();
                
                Application::$app->controller = $controller;//setting controller instance in app->controller
                $controller->action = $callback[1]; //saving controller method in model action.
                $callback[0] = $controller; //saving controller instance in callback
        
                //Executing middlewares
                foreach($controller->getMiddlewares() as $middleware) {
                    $middleware->execute();
                }
            }

            return call_user_func($callback, $this->request, $this->response, $this->params);

        } else {
            throw new NotFoundException();
        }
    }
}

?>