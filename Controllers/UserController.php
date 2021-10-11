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
    protected Imgs $imgs;

    public function __construct()
    {
        $this->user = new User();
        $this->article = new Article();
        $this->imgs = new Imgs();
    }

    //Found user profile specified on value from router
    public function getUser($response, $request, $id)
    {
        if(array_key_exists('param', $id)) {

            $userId = $id['param'];
            $result = $this->user->getUserProfile($userId, $this->imgs->tableName()); //--------------Testing 

            //If user model doesn't found data in db, throw new exception.
            if(!$result){
                throw new NotFoundException();
            }

            if($this->article->getCountOfUserArticles($userId) > 0){
                $pagination = new Paginator($this->article->getCountOfUserArticles($userId), 3);
                $pagArr = $pagination->getPagesArray();

                if(!$pages = $pagination->getPage()){
                    throw new NotFoundException();
                }
    
                //problem in last row, it doesnt have limit and always return all records in front of her.
                //Article model recieves array for limit. If page contain one row, passed array [page -1, page] to show one row from db.
                $userArticles = $this->article->getUserArticlesForCards($pages, $userId, $this->imgs->tableName());
            } else {
                $userArticles = [];
            }
            
            return $this->render('User/user', [
                'model' => $result,
                'userArticles' => $userArticles,
                "pagArr" => $pagArr ?? []
            ]);
        } else {
            throw new NotFoundException();
        }
    }

    //Return user own profile specified on value from session.
    public function getUserProfile()
    {
        if ($val = Application::$app->session->get('user')) {

            $obj = $this->user->getUserProfile($val, $this->imgs->tableName());

            //If user model doesn't found data in db, throw new exception.
            if(!$obj){
                throw new NotFoundException();
            }

            if($this->article->getCountOfUserArticles($val) > 0){
                $pagination = new Paginator($this->article->getCountOfUserArticles($val), 4);
                $pagArr = $pagination->getPagesArray();

                if(!$pages = $pagination->getPage()){
                    throw new NotFoundException();
                }
    
                //problem in last row, it doesnt have limit and always return all records in front of her.
                //Article model recieves array for limit. If page contain one row, passed array [page -1, page] to show one row from db.
                $userArticles = $this->article->getUserArticlesForCards($pages, $val, $this->imgs->tableName());
            } else {
                $userArticles = [];
            }
            
            return $this->render('User/profile', [
                'model' => $obj,
                'userArticles' => $userArticles,
                "pagArr" => $pagArr ?? []
            ]);
        } else {
            throw new NotFoundException();
        }
    }

    //Изменения статьи.
    public function edit($id)
    {

    }
}

?>