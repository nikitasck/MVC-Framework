<?php

namespace app\Core;

class View
{
    //Replacing {{content}} with subject at layout
    public function contentSection($subject, $layout)
    {
        return str_replace("{{content}}", $subject, $layout);
    }

    //Render layout. If needed pass parameters, pass array in this method.
    public function renderLayout($params = [])
    {
        //Default application layout
        $layout = Application::$app->layout;

        //If controller have layout, set it.
        if(Application::$app->controller) { 
            $layout = Application::$app->controller->layout;
        }

        //Define variables with $params array data.
        foreach($params as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include_once Application::$rootDir."/Views/Layouts/$layout.php";//Подключение жиректории, в которой находится шаблон для отрисовки.
        return ob_get_clean();
    }

    //Render view on layout. If needed pass parameters, pass array in this method. Parameters pass to the layout too.
    public function renderView($view, array $params = [])
    {

        //Define variables with $params array data.
        foreach($params as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include_once Application::$rootDir."/Views/$view.php";
        return ob_get_clean();
    }

    //Setting view to layout
    public function resolveView($view, $params = [])
    {
        $viewContent = $this->renderView($view, $params);
        $viewLayout = $this->renderLayout($params);
        //Replacing sections {{content}} with content.
        return $this->contentSection($viewContent, $viewLayout);
    }

    //Outputing string in base layout.
    public function inputContent(string $content)
    {
        $viewLayout = $this->renderLayout();
        return $this->contentSection($content, $viewLayout);
    }
}

?>