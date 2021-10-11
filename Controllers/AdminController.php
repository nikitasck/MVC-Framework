<?php 

namespace app\Controllers;

use app\Core\Application;
use app\Core\Controller;
use app\Models\Role;
use app\Models\Article;
use app\Models\User;
use app\Core\exception\ForbiddenExcepention;
use app\Core\exception\NotFoundException;
use app\Core\middleware\AdminMiddleware;
use app\Core\Paginator;
use app\Models\Imgs;
use Exception;

class AdminController extends Controller
{
    protected User $user;
    protected Article $article;
    protected Imgs $imgs;
    protected Role $role;

    public function __construct()
    {
        $this->user = new User();
        $this->article = new Article();
        $this->imgs = new Imgs();
        $this->role = new Role();
        $this->registerMiddleware(new AdminMiddleware(['adminPanel', 'showAllArticles', 'showAllUsers']));
    }

    public function adminPanel()
    {
        $this->setLayout('admin');
        $adminInfo['users'] = $this->user->getRowsCount();
        $adminInfo['articles'] = $this->article->getRowsCount();


        return $this->render('Admin/admin', [
            "adminInfo" => $adminInfo
        ]);
    }

    public function showAllArticles()
    {
        $articles = new Article();
        $model = $articles->getAllFromTable();
        $arrTest = [];
        //Получать от сюда массив articles[id => title]
        foreach($model as $key => $value){
            $arrTest += [$value->id => $value->title];
        }

        return $this->render('Admin/articles', [
            "model" => $arrTest
        ]);
    }

    public function showAllUsers()
    {
        $pagination = new Paginator($this->user->getRowsCount(), 10);
        $pagArr = $pagination->getPagesArray();

        if($this->user->getRowsCount() > 0){
            if(!$page = $pagination->getPage()){
                throw new NotFoundException();
            }

            $userList = $this->user->getUsersForList($page, $this->imgs->tableName());
        } else {
            $userList = [];
        }

        return $this->render('Admin/users', [
            "users" => $userList,
            "pagArr" => $pagArr
        ]);
    }
}

?>