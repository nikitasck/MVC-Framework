<?php 

namespace app\Core\exception;
use app\Core\Application;

class NotFoundException extends \Exception
{
    protected $message = "Page not found";
    protected $code = 404;
}

?>