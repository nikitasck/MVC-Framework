<?php

namespace app\Core;

/*
Класс отвечает за ответ http или сервера.

Метод setstatuscode - устанавливает код ответа http
Метод redirect - Устанавливает заголовок http, по сути перенаправляет по полученному маршруту
*/

class Response
{
    public function setStatusCode( $code)
    {
        http_response_code($code);
    }

    public function redirect($url)
    {
        header('Location: ' . $url);
    }
}

?>