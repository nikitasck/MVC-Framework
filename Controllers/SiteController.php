<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Application;
use app\Core\Paginator;
use app\Models\Article;
use app\Models\User;
use app\Models\Imgs;
use app\core\exception\NotFoundException;

class SiteController extends Controller
{
    protected Article $article;
    protected Imgs $imgs;

    public function __construct()
    {
        $this->article = new Article();
        $this->imgs = new Imgs();
    }

    //Getting from Article model 6 latest articles and then outputting homepage with them.
    public function home()
    {
        //Getting 6 articles.
        $articleCards = $this->article->getArticlesForCards([3], $this->imgs->tableName());

        return $this->render('home', [
            'model' => $articleCards
        ]);
    }

    public function aboutMe()
    {
        return $this->render('/Aboutme/aboutMe', []);
    }

    public function getArticles()
    {
        $pagination = new Paginator($this->article->getRowsCount(), 4);
        $pagArr = $pagination->getPagesArray();
        
        if($this->article->getRowsCount() > 0){
            if(!$page = $pagination->getPage()){
                throw new NotFoundException();
            }
            $articlesCards = $this->article->getArticlesForCards($page, $this->imgs->tableName());
        } else {
            $articlesCards = [];
        }

        

        //render
        return $this->render('Articles/articles',[
            "articles" => $articlesCards,
            "pagArr" => $pagArr ?? []
        ]

        );
    }

    
}

?>