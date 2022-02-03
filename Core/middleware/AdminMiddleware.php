<?php 

namespace app\Core\middleware;
use app\Core\Application;
use app\Core\exception\NotFoundException;

class AdminMiddleware extends BaseMiddleware
{

    public array $actions;

    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    public function execute()
    {
        if(!Application::$app->isAdmin){
            if(empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
                throw new NotFoundException();
            }
        }
    }
}

?>