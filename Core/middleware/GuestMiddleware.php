<?php 

namespace app\Core\middleware;
use app\Core\Application;
use app\core\exception\ForbiddenExcepention;

class GuestMiddleware extends BaseMiddleware
{

    public array $actions;

    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    public function execute()
    {
        if(Application::isGuest()){
            if(empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
                throw new ForbiddenExcepention();
            }
        }
    }
}

?>