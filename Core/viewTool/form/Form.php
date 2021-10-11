<?php 

namespace app\Core\viewTool\form;
use app\Core\Model;

class Form
{
    public static function begin($action, $method, $encType = '')
    {
        echo '<div class="container w-50">';
        echo sprintf('<form action="%s" method="%s" enctype="%s">', $action, $method, $encType);
        return new Form();
    }

    public static function end()
    {
        echo '</div>';
        echo '</form>';
    }

    public function field(Model $model, $attribute)
    {
        return new Field($model, $attribute);
    }

    public function textArea(Model $model, $attribute)
    {
        return new TextArea($model, $attribute);
    }

    public function button(string $value)
    {
        return new Button($value);
    }

    public function maxFileSize($size)
    {
        return new MaxFileSize($size);
    }
}

?>