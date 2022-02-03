<?php

namespace app\Core;
use app\Core\db\Database;
use app\Core\Response;
use app\Core\Request;
use app\Core\Router;
use app\Core\Controller;
use app\Core\Session;
use app\Models\Role;

class Application
{
    public static Application $app;
    public static $rootDir;
    public Database $db;
    public Response $response;
    public Request $request;
    public Router $router;
    public ?Controller $controller = null;
    public ?UserModel $user;
    public View $view;
    public Session $session;
    public String $layout;
    public string $userClass;
    public Bool $isAdmin = false;
    public Role $role;

    public function __construct($rootDir, Array $config)
    {
        self::$app = $this;
        self::$rootDir = $rootDir;
        $this->response = new Response();
        $this->request = new Request();
        $this->router = new Router($this->request, $this->response);
        $this->view = new View();
        $this->session = new Session();
        $this->userClass = $config['user'];
        $this->role = new Role();
        $this->layout = 'main';
        $this->db = new Database($config['db']);
        $this->setUser($this->session->get('user'));
    }

    //Set user.
    public function setUser($primaryValue)
    {
        if($primaryValue) {
            $userClassObj = new $this->userClass();
            $primaryKey = $userClassObj->primaryKey();
            $this->user = $userClassObj->findOne([$primaryKey => $primaryValue]);
            if($this->role->premission($primaryValue)){
                $this->isAdmin = true;
            } else {
                $this->isAdmin = false;
            }
        } else {
            $this->user = null;
        }
    }

    //Start application
    public function run()
    {
        try {
            echo $this->router->resolve();
        } catch(\Exception $e){
            $this->response->setStatusCode($e->getCode());
            echo $this->view->resolveView('Errors/_error', [
                'exception' => $e
            ]);
        }
    }

    public function login(UserModel $user)
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        
        $this->session->set('user', $primaryValue);
        return true;
    }

    public function logOut()
    {
        $this->user = null;
        $this->isAdmin = false;
        $this->session->remove('user');
        return true;
    }
    

    public static function isGuest()
    {
        return !self::$app->user;
    }
}

?>