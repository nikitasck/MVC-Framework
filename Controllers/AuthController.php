<?php 
namespace app\Controllers;

use app\Core\Application;
use app\Core\Controller;
use app\Models\User;
use app\Models\LoginForm;
use app\Models\Imgs;
use app\Core\Request;
use app\core\middleware\AuthMiddleware;

class AuthController extends Controller
{
    protected User $user;
    protected Imgs $img;
    protected LoginForm $loginForm;

    public function __construct()
    {
        $this->user = new User;
        $this->img = new Imgs;
        $this->loginForm = new LoginForm;
        $this->registerMiddleware(new AuthMiddleware(['register', 'login']));
    }

    public function register(Request $request)
    {
        if($request->isPost()) {
            $this->user->loadData($request->getBody());
            $this->img->loadData($request->getBody());

            if($this->user->validation()){
                if($this->img->setDefaultUserImage() && $this->img->save()){
                    $this->user->img_id = $this->img->lastInsertId();
                    if(isset($this->user->img_id) && $this->user->save()) {
                        Application::$app->session->setFlash('success', 'Registration succussful!');
                        Application::$app->response->redirect('/');
                    }
                }
            }
        }
        return $this->render('Registration/register', [
            "model" => $this->user,
        ]);
    }

    public function login(Request $request)
    {
        if($request->isPost()) {
            $this->loginForm->loadData($request->getBody());

            if($this->loginForm->validation() && $this->loginForm->login()) {
                Application::$app->response->redirect('/');
            }
        }
        return $this->render('Login/login', [
            "model" => $this->loginForm
        ]);
        
    }

    public function logout()
    {
        if(Application::$app->logOut()){
            Application::$app->response->redirect('/');
        }
    }

}

?>