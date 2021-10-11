<?php 

namespace app\Core\viewTool\pagination;


class Page
{
    protected $page;
    protected $url;

    public function __construct($page, $url)
    {
        $this->page = $page;
        $this->url = $url;
    }

    public function __toString()
    {
        return sprintf('
                <li class="page-item"><a class="page-link" href="%s?page=%s">%s</a></li>
        '
        ,
        $this->url,
        $this->page,
        $this->page
    );
    }
}

?>