<?php

namespace app\Core;

/*

Класс для принятия и обработки запросов
get url - получает с сервера url и обрезает его до первого знака '?'(Чтобы отсечь get запросы http)
get method - получает с сервера тип запроса http и возвращает его 
get body - Возвращает запрос целиком, разделяем массив на get и post запросы

*/


class Request
{
    public function getUrl()
    {
        // ---------------------------После завершения всего приложения, протестировать: подставить в Server свойство PATH_INFO
        //Отсекаем все, что после '?'
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');

        //Если url пусто, то взрвращаем пустой путь
        if($position === false) {
            return $path;
        }

        /*Отсекаем запрашиваемый url
            Допустим, путь выглядит так sit.com/contact/about-us?id=1, этот метод возвращает такой результат: /contact/about-us
        */
        $url = substr($path, 0, $position);

        return $url;
    }

    //возврат идет в нижнем регистре
    public function getMethod()
    {
        $method = strtolower($_SERVER['REQUEST_METHOD']);

        return $method;
    }

    //Является ли метод get?
    public function isGet()
    {
        return $this->getMethod() === 'get';
    }

    //Является ли метод get?
    public function isPost()
    {
        return $this->getMethod() === 'post';
    }

    //Получения Данные из переменных  get и post
    public function getBody()
    {
        $body = [];

        if($this->getMethod() === 'get') { //Я думаю, что сравнение здесь не нужно, надо будет затестить!
            foreach($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);//Очищаем входные данные от не нужных символов
            }
        }

        if($this->getMethod() === 'post') { //Я думаю, что сравнение здесь не нужно, надо будет затестить!
            foreach($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);//Очищаем входные данные от не нужных символов
            }
        }

        return $body;
    }
}
?>