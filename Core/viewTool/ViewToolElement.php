<?php 

namespace app\Core\viewTool;
use app\Core\Model;

abstract class ViewToolElement
{
    protected $model;
    protected string $attribute;

    public function __construct($model, $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }

    abstract public function __toString();
}

?>