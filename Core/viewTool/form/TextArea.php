<?php 

namespace app\Core\viewTool\form;
use app\Core\viewTool\viewToolElement;
use app\Core\Model;

class TextArea extends ViewToolElement
{
    //Преобразование класса в строку(если будем его выводить)
    public function __toString()
    {
        return sprintf('  
        <div class="form-group m-1">
        <label>
            %s
        </label>

        <textarea rows="5" cols="30" name="%s" value="%s" placeholder="Enter %s" class="form-control%s"></textarea>

        <div class="invalid-feedback">
            %s
        </div>
      </div>',
        
        $this->model->getLabel($this->attribute),
        $this->attribute,
        $this->model->{$this->attribute},
        $this->model->getLabel($this->attribute),
        $this->model->hasError($this->attribute) ? ' is-invalid' : '',
        $this->model->getFirstError($this->attribute)
    );
    }
}

?>