<?php 

namespace app\Controllers;

use app\Core\Controller;
use app\Core\Request;
use app\Models\User;
use app\Core\Application;
use app\Core\Paginator;
use app\Models\Article;
use app\Models\Imgs;
use app\core\exception\NotFoundException;

class UserController extends Controller
{
    protected User $user;
    protected Article $article;
    protected Imgs $img;

    public function __construct()
    {
        $this->user = new User();
        $this->article = new Article();
        $this->img = new Imgs();
    }

    //Get user object recived from id value.
    public function getUser($response, $request, $id)
    {
        if(array_key_exists('param', $id)) {

            $userId = $id['param'];
            $userObj = $this->user->getUserProfile($userId, $this->img->tableName());

            //If user doesn's exists.
            if(!$userObj){
                throw new NotFoundException();
            }

            //Check amount of user articles. 
            if($this->article->getCountOfUserArticles($userId) > 0){
                $pagination = new Paginator($this->article->getCountOfUserArticles($userId), 3);

                if(!$pages = $pagination->getPage()){
                    throw new NotFoundException();
                }
                $userArticles = $this->article->getUserArticlesForCards($pages, $userId, $this->img->tableName());
            } else {
                $userArticles = [];
            }
            
            return $this->render('User/user', [
                'model' => $userObj,
                'userArticles' => $userArticles,
                "pagArr" => $pagination->getPagesArray() ?? []
            ]);
        } else {
            throw new NotFoundException();
        }
    }

    //Return user own profile recived on value from session.
    public function getUserProfile()
    {
        if ($userId = Application::$app->session->get('user')) {

            $userObj = $this->user->getUserProfile($userId, $this->img->tableName());

            //If user doesn's exists.
            if(!$userObj){
                throw new NotFoundException();
            }

            if($this->article->getCountOfUserArticles($userId) > 0){
                $pagination = new Paginator($this->article->getCountOfUserArticles($userId), 4);

                if(!$pages = $pagination->getPage()){
                    throw new NotFoundException();
                }
    
                $userArticles = $this->article->getUserArticlesForList($pages, $userId, $this->img->tableName());
            } else {
                $userArticles = [];
            }
            
            return $this->render('User/profile', [
                'model' => $userObj,
                'userArticles' => $userArticles,
                "pagArr" => $pagination->getPagesArray() ?? []
            ]);
        } else {
            throw new NotFoundException();
        }
    }

    //Remove the specified user from storage.
    public function deleteUser($request,$response, $id)
    {
        $id = $id['param'];
        $userObj = $this->user->getOneUser($id);

        if($userObj){
            if(Application::$app->session->get('user') === $userObj->id || Application::$app->isAdmin){
                if($this->user->deleteRow($id)){
                    Application::$app->session->setFlash('success', 'User deleted');
                    Application::$app->response->redirect('/');
                }
                if(Application::$app->isAdmin){
                    Application::$app->session->setFlash('success', 'User deleted');
                    Application::$app->response->redirect('/admin');    
                }
            }
        }
    }

    //Edit user object recived from id value.
    //Edit permission have admin and user(determined on recived value 'user' from session).
    public function editUser($request,$response, $id)
    {
        $id = $id['param'];
        $userObj = $this->user->getOneUser($id);

        if(Application::$app->session->get('user') === $userObj->id || Application::$app->isAdmin){
            if($request->isPost()) {
                $this->user->loadData($request->getBody());
                $this->img->loadData($request->getBody());

                //If the new data is validated it will be updated, otherwise the unverified data will be returned to the view.
                if($this->user->validation()){
                    if($this->img->validation()){
                        if($this->img->uploadImg() && $this->img->save()){
                            $this->user->img_id = $this->img->lastInsertId();
                        }
                    }
                    if($this->user->updateFilledAttributesForRow($id)){
                        Application::$app->session->setFlash('success', "User profile: $id edited");
                        Application::$app->response->redirect("/user/$id");
                    }
                } else {
                    $userObj = $this->user;
                    echo 'or here?';
                    exit;
                }
            }
            //Set layout for admin panel
            if(Application::$app->isAdmin){
                $this->setLayout('admin');
            }
            return $this->render('User/editUser', [
                'model' => $userObj,
                'img' => $this->img
            ]);
        } else {
            throw new NotFoundException();
        }
    }

    
}

?>