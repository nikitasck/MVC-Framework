<?php

namespace app\Core;
use app\Core\db\Database;
use app\Core\Response;
use app\Core\Request;
use app\Core\Router;
use app\Core\Controller;
use app\Core\Session;

/*
Главный класс, включает в себя приложение, которое запускается при переходе на index.php. Думаю, что надо сделать этот класс синглтон или чет такое.
*/

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

    public function __construct($rootDir, Array $config)
    {
        self::$app = $this; // Создаем сингл тон нашего приложени.
        self::$rootDir = $rootDir;
        $this->response = new Response();
        $this->request = new Request();
        $this->router = new Router($this->request, $this->response);
        $this->view = new View();
        $this->session = new Session();
        $this->userClass = $config['user'];
        
        $this->layout = 'main';

        $this->db = new Database($config['db']);

        //Загрузка идентификатора пользователя из сессии. Загрузка по идентификатору модели пользователя.
        $primaryValue = $this->session->get('user');

        if($primaryValue) {
            $userClassObj = new $this->userClass();//Класс пользователя получаем при инициализации приложения
            $primaryKey = $userClassObj->primaryKey();//Получаем название идентификатора пользователя
            $this->user = $userClassObj->findOne([$primaryKey => $primaryValue]);//Используя метод поиска пользователей в модели, загружаем в приложение обьект пользователя.
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
            $this->response->setStatusCode($e->getCode());//Здесь реализация сингл тона. Мы обратились к сущности/обьекту класса апп и  из него использовали метод устоновки кода ответа http класса responseCode
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
        $this->admin = false;
        $this->session->remove('user');

    }

    public static function isGuest()
    {
        return !self::$app->user;
    }
}

?>