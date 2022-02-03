<?php 

namespace app\Controllers;

use app\Core\Controller;
use app\Models\Role;
use app\Models\Article;
use app\Models\User;
use app\Core\exception\NotFoundException;
use app\Core\middleware\AdminMiddleware;
use app\Core\Paginator;
use app\Models\Imgs;

class AdminController extends Controller
{
    protected User $user;
    protected Article $article;
    protected Imgs $imgs;
    protected Role $role;
    protected $adminInfo = [];

    public function __construct()
    {
        $this->setLayout('admin');
        $this->user = new User();
        $this->article = new Article();
        $this->imgs = new Imgs();
        $this->role = new Role();
        $this->registerMiddleware(new AdminMiddleware(['adminPanel', 'showAllArticles', 'showAllUsers']));
        $this->adminInfo['users'] = $this->user->getRowsCount();
        $this->adminInfo['articles'] = $this->article->getRowsCount();
    }

    //Display a main page of the admin panel.
    public function adminPanel()
    {
        return $this->render('Admin/admin', [
            "adminInfo" => $this->adminInfo
        ]);
    }

    //Display a list of the users in admin panel.
    public function getUsers()
    {
        if($this->user->getRowsCount() > 0){
            $pagination = new Paginator($this->user->getRowsCount(), 10);
            
            if(!$page = $pagination->getPage()){
                throw new NotFoundException();
            }

            $userList = $this->user->getUsersForList($page, $this->imgs->tableName());
        } else {
            $userList = [];
        }

        return $this->render('Admin/users', [
            'users' => $userList,
            'pagArr' => $pagination->getPagesArray(),
            'adminInfo' => $this->adminInfo
        ]);
    }

    //Display a list of the articles in admin panel.
    public function getArticles()
    {
        if($this->article->getRowsCount() > 0){
            $pagination = new Paginator($this->article->getRowsCount(), 10);

            if(!$page = $pagination->getPage()){
                throw new NotFoundException();
            }

            $articleList = $this->article->getArticlesForList($page, $this->imgs->tableName());
        } else {
            $articleList = [];
        }

        return $this->render('Admin/articles', [
            'articles' => $articleList,
            'pagArr' => $pagination->getPagesArray(),
            'adminInfo' => $this->adminInfo
        ]);
    }
}

?>