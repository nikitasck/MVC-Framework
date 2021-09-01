<?php 

namespace app\Core\exception;

class ForbiddenExcpention extends \Exception
{
    protected $message = 'You dont have primission to this page! Please, loggin first!';
    protected $code = 403;
}

?>