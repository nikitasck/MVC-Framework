<?php 

namespace app\Core\viewTool\table;
use app\core\viewTool\ViewToolElement;

class ListItem
{
    protected $url;
    protected $key;
    protected $attribute;
    protected array $button;

    public function __construct($url, $key, $attribute, $button = [])
    {
        $this->url = $url;
        $this->key = $key;
        $this->attribute = $attribute;
        $this->button = $button;
    }

    public function __toString()
    {
            return sprintf('
            <li class="list-group-item">
                <div class="container">
                    <div class="row">
                        <div class="col-10">
                            <a href="%s%s">%s</a><br>
                        </div>

                        <div class="col-2">
                        %s
                        </div>
                    </div>
                </div>
            </li>
            ',
            $this->url,
            $this->key,
            $this->attribute,
            $this->addButtons()
            );
    }

    public function addButtons()
    {
        $buttons = '';
        foreach($this->button as $key => $value){
            $buttons .= '<a href="' . $key . '" class="border border-secondary rounded link-dark text-decoration-none p-2">' . $value . '</a>';
        }
        return $buttons;

    }
}

?>