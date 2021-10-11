<?php 

namespace app\Controllers;

use app\Core\Controller;
use app\Core\Request;
use app\Core\Application;
use app\Core\exception\NotFoundException;
use app\Models\Article;
use app\Models\Imgs;
use app\Core\middleware\GuestMiddleware;
use app\Models\User;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->registerMiddleware(new GuestMiddleware(['addArticle']));
    }

    /*
    Метод для добавления статей
    */
    public function addArticle(Request $request)
    {
        $article = new Article();
        $img = new Imgs();

        

        if($request->isPost()) {
            //Firstly, upload image into the Img model, then pass Img id into article model ang registrate this.
            $article->loadData($request->getBody());
            $img->loadData($request->getBody());
            //установка айди пользователя из сессии
            $article->user_id = Application::$app->session->get('user');

            //тесты
            if($article->validation() && $img->validation() && isset($article->user_id)){
                if($img->uploadImg() && $img->save()){
                    $article->img_id = $img->lastInsertId();
                    
                    if(isset($article->img_id) && $article->save()) {
                        Application::$app->session->setFlash('success', 'Article added');
                        //Подумать, куда перенаправить пользователя, скорее всего на страницу статей пользователя или на страницу статьи
                        Application::$app->response->redirect('/');
                    }
                }
            }
            
            
            /*
            if($img->validation() && $img->uploadImg() && $img->save() && ($img_id = $img->lastInsertId())){
                //Здесь должна быть магия. Нужно получить id созданной строки.
                echo var_dump($img_id);
            }
            if($article->validation() && isset($article->user_id) && $article->save()) {
                Application::$app->session->setFlash('success', 'Article added');
                //Подумать, куда перенаправить пользователя, скорее всего на страницу статей пользователя или на страницу статьи
                Application::$app->response->redirect('/');
            }
            */
        }

        return $this->render('Articles/addArticle', [
            'model' => $article,
            'img' => $img
        ]);
    }

    //Получение всех статей
    public function getArticles()
    {
        //Получить из таблицы все записи из базы
        //вернуть на домашнюю страницу массив этих записей

        $articles = new Article();

        return $this->render('home', [
            
        ]);
    }

    public function getArticlePage($response, $request, $id)
    {
        $articles = new Article();
        $imgs = new Imgs();
        $user = new User();
        $id = $id['param'];

        $obj = $articles->getArticleObjForPage($id, $imgs->tableName(), $user->tableName());
        $articleCards = $articles->getArticlesForCards([3], $imgs->tableName());
        return $this->render('Articles/article', [
            'model' => $obj,
            'articleCards' => $articleCards
        ]);

    }

    //Получение статей(множества) пользователя
    public function getUserarticles($userId)
    {

    }
    
    //Получения статьи(одной) пользователя
    public function getUserArticle($userId)
    {

    }



    //Изменения статьи.
    public function edit($id)
    {

    }
}

?>