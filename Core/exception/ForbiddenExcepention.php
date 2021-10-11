<?php 

namespace app\Core\exception;

class ForbiddenExcepention extends \Exception
{
    protected $message = 'You dont have primission to this page! Please, loggin first!';
    protected $code = 403;
}

?>