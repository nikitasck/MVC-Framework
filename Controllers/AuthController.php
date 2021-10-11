<?php 
namespace app\Controllers;

use app\Core\Application;
use app\Core\Controller;
use app\Core\exception\ForbiddenExcpention;
use app\Models\User;
use app\Models\LoginForm;
use app\Models\Imgs;
use app\Core\Request;
use app\Core\Response;
use app\core\middleware\AuthMiddleware;
use app\core\middleware\GuestMiddleware;
use app\Models\Role;

/*
Класс предаставляет методы регистрации, логина, логаута и другие относящиеся к авторизации.
*/

class AuthController extends Controller
{
    
    public function __construct()
    {
        $this->registerMiddleware(new AuthMiddleware(['register', 'login']));
    }

    public function register(Request $request)
    {
        /*
        Создать модель пользователя user
        Получил из запроса данные getBody[post]
        Передал бы эти данные в метод модели User->loadData()
        Проверил, если все ок, то использовал User->save()
        При успешно регистрации, вывел бы flash сообщение, что все хорошо, и отправил ответ http 200-ok(или какой там нужен код)
        */

        $user = new User();
        $img = new Imgs();

        if($request->isPost()) {
            //загрузка данных в атрибуты модели
            $user->loadData($request->getBody());
            $img->loadData($request->getBody());

            if($user->validation() && $img->validation()){
                if($img->uploadImg() && $img->save()){
                    $user->img_id = $img->lastInsertId();
                    if(isset($user->img_id) && $user->save()) {
                        Application::$app->session->setFlash('success', 'Registration succussful!');
                        //Подумать, куда перенаправить пользователя, скорее всего на страницу статей пользователя или на страницу статьи
                        Application::$app->response->redirect('/');
                    }
                }
            }
        }

        //Отрисовка страницы при get запросе
        return $this->render('Registration/register', [
            "model" => $user,
            "img" => $img
        ]);
    }

    public function login(Request $request)
    {
        /*
        Возможно, создать модель авторизации, чтобы использовать метод validate().
        Создал пользователя
        Проверил метод isPost?
        Получил бы данные из запроса, подставил их в user->findOne() 
        установил flashMEssage в success
        В ссессию поместил идентификатор пользователя
        */


        $role = new Role();


        $user = new LoginForm();

        if($request->isPost()) {
            $user->loadData($request->getBody());

            echo var_dump($user);
                exit;
            $premissions = $role->premission($user);
            if($premissions == 'admin') {
                
                Application::$app->isAdmin = true;
            }
    
            if($user->validation() && $user->login()) {
                Application::$app->response->redirect('/');
            }

        }
        return $this->render('Login/login', [
            "model" => $user
        ]);
        
    }

    public function logout()
    {
        //Удалить юзеря из сессии
        //перенаправить на главную страницу
        Application::$app->isAdmin = false;
        Application::$app->logOut();
        Application::$app->response->redirect('/');
    }

}

?>