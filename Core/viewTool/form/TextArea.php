<?php 

namespace app\Core\viewTool\form;
use app\Core\viewTool\viewToolElement;
use app\Core\Model;
use Attribute;

class TextArea extends ViewToolElement
{
    public function __toString()
    {
        return sprintf('  
        <div class="form-group m-1">
        <label>
            %s
        </label>

        <textarea rows="5" cols="30" name="%s" placeholder="Enter %s" class="form-control%s">%s</textarea>

        <div class="invalid-feedback">
            %s
        </div>
      </div>',
        
        $this->model->getLabel($this->attribute),
        $this->attribute,
        $this->model->getLabel($this->attribute),
        $this->model->hasError($this->attribute) ? ' is-invalid' : '',
        $this->model->{$this->attribute},
        $this->model->getFirstError($this->attribute)
    );
    }
}

?>