<?php

namespace app\Core;


class Request
{
    public function getUrl()
    {
        //Cut off get requests.
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');

        if($position === false) {
            return $path;
        }

        $url = substr($path, 0, $position);

        return $url;
    }

    //Get request method from server
    public function getMethod()
    {
        $method = strtolower($_SERVER['REQUEST_METHOD']);

        return $method;
    }

    public function isGet()
    {
        return $this->getMethod() === 'get';
    }

    public function isPost()
    {
        return $this->getMethod() === 'post';
    }

    //Receiving data from request and returning it.
    public function getBody()
    {
        $body = [];

        if($this->getMethod() === 'get') {
            foreach($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if($this->getMethod() === 'post') {
            foreach($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $body;
    }
}
?>