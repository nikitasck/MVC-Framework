<?php 

namespace app\Core\viewTool\form;

class Button 
{
    protected const TYPE_BUTTON = 'button';
    protected const TYPE_SUBMIT = 'submit';
    protected const TYPE_RESET = 'reset';

    protected string $type = '';
    protected string $value = '';

    public function __construct($value)
    {
        $this->type = self::TYPE_BUTTON;
        $this->value = $value;
    }

    public function __toString()
    {
        return sprintf('
            <div class="container text-end mt-3 p-1">
                <button type="%s" class="btn btn-success">%s</button>
            </div>
        ',
        $this->type,
        $this->value
        );
    }

    public function submitButton()
    {
        $this->type = self::TYPE_SUBMIT;
        return $this;
    }

    public function resetButton()
    {
        $this->type = self::TYPE_RESET;
        return $this;
    }

}

?>