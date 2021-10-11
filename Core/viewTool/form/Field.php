<?php 

namespace app\Core\viewTool\form;
use app\Core\viewTool\viewToolElement;
use app\Core\Model;

class Field extends ViewToolElement
{
    protected const TYPE_TEXT = 'text';
    protected const TYPE_EMAIL = 'email';
    protected const TYPE_PASSWORD = 'password';
    protected const TYPE_FILE = 'file';

    protected string $type = '';

    public function __construct(Model $model, $attribute)
    {
        parent::__construct($model, $attribute);
        $this->type = self::TYPE_TEXT;
    }

    //Return pattern when echo this class.
    public function __toString()
    {
        return sprintf('  
        <div class="form-group m-1">
        <label>
            %s
        </label>

        <input type="%s" name="%s" value="%s" placeholder="Enter %s" class="form-control%s">

        <div class="invalid-feedback">
            %s
        </div>
        </div>',
        
        $this->model->getLabel($this->attribute),
        $this->type,
        $this->attribute,
        $this->model->{$this->attribute},
        $this->model->getLabel($this->attribute),
        $this->model->hasError($this->attribute) ? ' is-invalid' : '',
        $this->model->getFirstError($this->attribute)
        );
    }

    public function passwordField()
    {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }

    public function emailField()
    {
        $this->type = self::TYPE_EMAIL;
        return $this;
    }

    public function imageField()
    {
        $this->type = self::TYPE_FILE;
        return $this;
    }
}

?>