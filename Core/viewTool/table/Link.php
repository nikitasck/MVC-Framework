<?php 

namespace app\Core\viewTool\table;

class Link 
{
    protected $href;
    protected $style;

    public function __construct($href, $style = '')
    {
        $this->href = $href;
        $this->style = $style;
    }

    public function __toString()
    {
        return sprintf('
            
        ');
    }
}

?>