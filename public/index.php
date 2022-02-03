<?php

use app\Controllers\AuthController;
use app\Controllers\SiteController;
use app\Controllers\AdminController;
use app\Controllers\ModeratorController;
use app\Controllers\ArticleController;
use app\Controllers\UserController;
use app\Core\Application;

require_once __DIR__.'/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

//Configuration.
$config = [
    'user' => app\Models\User::class,
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'db_user' => $_ENV['DB_USER'],
        'db_password' => $_ENV['DB_PASSWORD'],
    ]
];

//Creating application instance
$app = new Application(dirname(__DIR__), $config);

$app->router->get('/', [SiteController::class, 'home']);

//Admin panel
$app->router->get('/admin', [AdminController::class, 'adminPanel']);
$app->router->get('/admin/articles', [AdminController::class, 'getArticles']);//Retrieving list of all articles at admin panel
$app->router->get('/admin/users', [AdminController::class, 'getUsers']);//Retrieving list of all users at admin panel
$app->router->get('/admin/articles/article/', [ArticleController::class, 'getArticle'], ['{id}']);//Retrieving article by id at admin panel
$app->router->get('/admin/users/user/', [UserController::class, 'getUser'], ['{id}']);//Retrieving article by id at admin panel

$app->router->get('/moderator', [ModeratorController::class, 'moderatorPanel']);

//Registration
$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);

//Login
$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);

//Lougout
$app->router->get('/logout', [AuthController::class, 'logout']);

//Creating article
$app->router->get('/add-article', [ArticleController::class, 'addArticle']);
$app->router->post('/add-article', [ArticleController::class, 'addArticle']);

//Updating article
$app->router->get('/edit/article/', [ArticleController::class, 'editArticle'], ['{id}']);
$app->router->post('/edit/article/', [ArticleController::class, 'editArticle'], ['{id}']);

//delete article
$app->router->get('/delete/article/', [ArticleController::class, 'deleteArticle'], ['{id}']);

//edit user
$app->router->get('/edit/user/', [UserController::class, 'editUser'], ['{id}']);
$app->router->post('/edit/user/', [UserController::class, 'editUser'], ['{id}']);
//delete user
$app->router->get('/delete/user/', [UserController::class, 'deleteUser'], ['{id}']);

$app->router->get('/article/', [ArticleController::class, 'getArticlePage'], ['{id}']);//Retrieving article by id
$app->router->get('/user/', [UserController::class, 'getUser'], ['{id}']);//Retrieving user by id

$app->router->get('/article', [ArticleController::class, 'getArticle']);

//About me page
$app->router->get('/about-me', [SiteController::class, 'aboutMe']);

//all aricles page
$app->router->get('/articles', [SiteController::class, 'getArticles']);

//user profile
$app->router->get('/profile', [UserController::class, 'getUserProfile']);

$app->run();

?>