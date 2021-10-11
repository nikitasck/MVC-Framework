<?php

use app\Controllers\AuthController;
use app\Controllers\SiteController;
use app\Controllers\AdminController;
use app\Controllers\ModeratorController;
use app\Controllers\ArticleController;
use app\Controllers\UserController;
use app\Core\Application;

require_once __DIR__.'/../vendor/autoload.php';
//storage dir

//Загрузка dotenv файла
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

//Этот массив передаем в конструктор приложения
$config = [
    'user' => app\Models\User::class,
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'db_user' => $_ENV['DB_USER'],
        'db_password' => $_ENV['DB_PASSWORD'],
    ]
];
$app = new Application(dirname(__DIR__), $config);

$app->router->get('/', [SiteController::class, 'home']);//Получение домашней страницы.

$app->router->get('/admin', [AdminController::class, 'adminPanel']);//Получение Админ панели.
$app->router->get('/moderator', [ModeratorController::class, 'moderatorPanel']);//Получение панли модератора.

$app->router->get('/admin/articles', [AdminController::class, 'showAllArticles']);//Получение всех записей админки.
$app->router->get('/admin/users', [AdminController::class, 'showAllUsers']);//Получение всех записей админки.
$app->router->get('/admin/articles/article/', [ArticleController::class, 'getArticle'], ['{id}']);//Если указан третий параметр в массиве, то последний индекс будет передаваться в метод.
$app->router->get('/admin/users/user/', [UserController::class, 'getUser'], ['{id}']);//Если указан третий параметр в массиве, то последний индекс будет передаваться в метод.

//Авторизация
$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);

$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);

//Добавление статей
$app->router->get('/add-article', [ArticleController::class, 'addArticle']);
$app->router->post('/add-article', [ArticleController::class, 'addArticle']);

//Получение статьи по айди
$app->router->get('/article/', [ArticleController::class, 'getArticlePage'], ['{id}']);
$app->router->get('/user/', [UserController::class, 'getUser'], ['{id}']);

$app->router->get('/article', [ArticleController::class, 'getArticle']);

//About me page
$app->router->get('/about-me', [SiteController::class, 'aboutMe']);

//all aricles page
$app->router->get('/articles', [SiteController::class, 'getArticles']);

//user profile
$app->router->get('/profile', [UserController::class, 'getUserProfile']);

$app->router->get('/logout', [AuthController::class, 'logout']);

$app->run();

?>