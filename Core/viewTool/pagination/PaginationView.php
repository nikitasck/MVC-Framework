<?php 

namespace app\Core\viewTool\pagination;

class PaginationView
{
    protected $model;
    protected $url;

    public function __construct($model, $url)
    {
        $this->model = $model;
        $this->url = $url;
    }

    public function fillPage()
    {
        echo '<nav aria-label="Page navigation example">';
        echo '<ul class="pagination justify-content-center mt-3">';
        foreach($this->model as $key => $value){
            echo $this->Page($value, $this->url);
        }

        echo '</ul>';
        echo '</nav>';
    }

    public function page($page, $url)
    {
        return new Page($page, $url);
    }
}

?>