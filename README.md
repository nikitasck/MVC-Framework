# Simple MVC article blog.
    Created for educational purposes only.

## Routes
    Routes sets in public/index.php.
    To set get route write: $app->router->get('url', Controller:class, method);
    To set get route and use controller method write: $app->router->get('url', [Controller:class, 'method']);
        If you want throw id for model write: $app->router->get('url', [Controller:class, 'method], ['{id}']);
        After it you can get id in your controller method.
    To set post route and use controller method write: $app->router->get('url', [Controller:class, 'method']);

## Controllers
    To create controller use app\Core\Controller and extend base Contorller.
    Base controller has some properties:
        $layout - view layout.
        $action - to save method for execution
        $middlewares - array of middlewares
    Controllers uses for rendering view, manipulation with data and passing it to the model.
## Models
    To create model use app\Core\DbModel and set methods: tableName(), primaryKey(), attributes().
    Model uses for validation, manipulation with database table(create, read, update, delete and etc).
    
## Views
    To render view in controller with base layout user render('view_path', [properties => '']) method:
        return $this->render('Articles/addArticle', [
            'model' => $this->article,
            'img' => $this->img
        ]);
    View uses setted controller layout and put content in curves{{}} from passed view. 
