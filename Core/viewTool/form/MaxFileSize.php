<?php 

namespace app\Core\viewTool\form;

class MaxFileSize
{
    protected $size = '';

    public function __construct($size)
    {
        $this->size = $size;
    }

    public function __toString()
    {
        return sprintf('
        <input type="hidden" name="MAX_FILE_SIZE" value="%s" />
        ',
        $this->size
        );
    }
}

?>