<?php 

namespace app\Controllers;

use app\Core\Controller;
use app\Core\Request;
use app\Core\Application;
use app\Core\exception\ForbiddenExcepention;
use app\Core\exception\NotFoundException;
use app\Models\Article;
use app\Models\Imgs;
use app\Core\middleware\GuestMiddleware;
use app\Models\User;

class ArticleController extends Controller
{
    protected $article;
    protected $user;
    protected $img;


    public function __construct()
    {
        $this->article = new Article();
        $this->user = new User();
        $this->img = new Imgs();
        $this->registerMiddleware(new GuestMiddleware(['addArticle']));
    }

    //Show the form for creating a new article and Store a newly created article in storage.
    public function addArticle(Request $request)
    {
        if($request->isPost()) {
            $this->article->loadData($request->getBody());
            $this->img->loadData($request->getBody());

            $this->article->user_id = Application::$app->session->get('user');

            if($this->article->validation() && isset($this->article->user_id)){
                if($this->img->validation()){
                    if($this->img->uploadImg() && $this->img->save()){
                        $this->article->img_id = $this->img->lastInsertId();    
                }                    
                    if(isset($this->article->img_id) && $this->article->save()) {
                        Application::$app->session->setFlash('success', 'Article added');
                        Application::$app->response->redirect('/article/'.$this->article->lastInsertId());
                    }
                }
            }
        }
        return $this->render('Articles/addArticle', [
            'model' => $this->article,
            'img' => $this->img
        ]);
    }

    //Display the specified article.
    public function getArticlePage($request, $response, $id)
    {
        $id = $id['param'];

        $articleObj = $this->article->getArticleObjForPage($id, $this->img->tableName(), $this->user->tableName());
        $articleCards = $this->article->getArticlesForCards([3], $this->img->tableName());
        return $this->render('Articles/article', [
            'model' => $articleObj,
            'articleCards' => $articleCards
        ]);

    }

    //Show the form for editing the specified resource and  Update the specified article in storage.
    public function editArticle(Request $request,$response, $id)
    {
        $id = $id['param'];
        $articleObj = $this->article->getOneArticle($id);

        if(Application::$app->session->get('user') === $articleObj->user_id || Application::$app->isAdmin)
        {
            if($request->isPost()) {
                $this->article->loadData($request->getBody());
                $this->img->loadData($request->getBody());

                if($this->article->validation()){
                    if($this->img->validation()){
                        if($this->img->uploadImg() && $this->img->save()){
                            $this->article->img_id = $this->img->lastInsertId();
                        }
                    }
                    if($this->article->updateFilledAttributesForRow($id)){
                        Application::$app->session->setFlash('success', 'Article edited');
                        Application::$app->response->redirect("/article/$id");
                    }
                } else {
                    $articleObj = $this->article;
                }
            }
            
            if(Application::$app->isAdmin){
                $this->setLayout('admin');
            }
            return $this->render('Articles/editArticle', [
                'model' => $articleObj,
                'img' => $this->img
            ]);
        } else {
            throw new NotFoundException();
        }
    }

    //Remove the specified article from storage.
    public function deleteArticle($request,$response, $id)
    {
        $id = $id['param'];
        $articleObj = $this->article->getOneArticle($id);

        if($articleObj){
            if(Application::$app->session->get('user') === $articleObj->user_id || Application::$app->isAdmin){
                if($this->article->deleteRow($id)){
                    Application::$app->session->setFlash('success', 'Article deleted');
                    Application::$app->response->redirect('/');
                    if(Application::$app->isAdmin){
                        Application::$app->session->setFlash('success', 'Article deleted');
                        Application::$app->response->redirect('/admin');    
                    }
                }
            }
        }

    }
}

?>