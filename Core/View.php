<?php

namespace app\Core;

/*
    Переделать представление таким образов, чтобы можно было в самом представлении использовать @extend для расширении шаблонов.
    Убрать свойство (шаблона) представлений через контролер
    Все, что связано с представлением, должно обробатываться тут.
*/

class View
{


    //Заменяет участок {{content}} на вью или контент(текст). Принимает в себя что нужно заменить($subject) и где($layout)
    public function contentSection($subject, $layout)
    {
        return str_replace("{{content}}", $subject, $layout);
    }

    /*
    Загружает шабон, так называемый макет.
    */
    public function renderLayout($params = [])
    {
        $layout = Application::$app->layout; //Базовый шаблон приложения

        //Если указан вью в контроллере, то извлекаем из него шаблон.
        if(Application::$app->controller) { // --------------------Спорно, поскольку обязуем пользователя каждый раз указывать, какой шаблон ему нужен, я бы эту возможность передал представлению
            $layout = Application::$app->controller->layout;
        }

        foreach($params as $key => $value) {
            $$key = $value;
        }
        //Буферизация нужна, так как если скотпрь начел отправку заголовков, могут быть проблемы с отображением
        //Вк
        ob_start();
        include_once Application::$rootDir."/Views/Layouts/$layout.php";//Подключение жиректории, в которой находится шаблон для отрисовки.
        return ob_get_clean();
    }

    //Подключает представление(которое расширяет шаблон)
    public function renderView($view, array $params = [])
    {

        //Для передачи переменных в представление, напишем цикл подстановки из получаемого массива. Название ключей массива соответствует имени переменных в представлении
        foreach($params as $key => $value) {
            $$key = $value;
        }

        //Теперь подключаем представление
        ob_start();
        include_once Application::$rootDir."/Views/$view.php";
        return ob_get_clean();
    }

    // Подстановка в макет представления в поле {{content}} 
    public function resolveView($view, $params = [])
    {
        $viewContent = $this->renderView($view, $params);
        $viewLayout = $this->renderLayout($params); //Прорисовывыем Шаблон
        //Заменяем участки в шаблоне(layout) {{content}} на представление content
        return $this->contentSection($viewContent, $viewLayout);
    }

    /*
    Подстановка в базовый шаблон текста или контента.
    */
    public function inputContent($content)
    {
        $viewLayout = $this->renderLayout();
        return $this->contentSection($content, $viewLayout);
    }
}

?>