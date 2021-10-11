<?php 

namespace app\Core\viewTool\cardField;


class Card
{
    protected $model;
    protected $button = '';

    
    public function __construct($model)
    {
        $this->model = $model;
    }

    public function __toString()
    {
        return sprintf('
        <div class="col">
            <a href="/article/%s" class="link-dark text-decoration-none">
                <div class="card shadow-sm">
                    <div class="row">
                    <div class="col">
                    <img src="%s" class="card-img img-thumbnail">
                    </div>
                    </div>

                    <div class="card-body">
                        <p class="card-text">%s</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">Created: %s</small>
                        </div>
                        %s
                    </div>
                </div>
            </a>
        </div>

        ',
        $this->model->id,
        $this->model->src,
        $this->model->title,
        $this->model->created_at,
        $this->button
        );
    }

    //Create layout for button group and pass array of button values [[buttonData1], [buttonData2], ...]
    public function setButtons(array $buttons)
    {
        $bttn = $this->generatingButtonsValues($buttons);
        $this->button = sprintf('
        <div class="d-flex justify-content-between align-items-center mt-2">
        <div class="btn-group">
                %s
        </div>
        </div>',
        $bttn
        );

    return $this;
    }

    //for every buttton data generating link, and chek if it contains passing value in url: localhost:/url/3
    public function generatingButtonsValues(array $buttons)
    {
        $bttn = '';
        foreach($buttons as $key => $value){
            if(count($value) == 2){
                $bttn .= $this->generateLink($value[0],$value[1]);
            }
            if(count($value) == 3){
                $bttn .= $this->generateLink($value[0],$value[1],'/' . $value[2]);
            }
        }
        return $bttn;
    }

    //generate links(buttons) with received data.
    public function generateLink($url, $text, $urlId = '')
    {
        return sprintf('
        <a href="%s%s" class="border border-secondary link-dark text-decoration-none p-1 mt-2">%s</a>
        ',
        $url,
        $urlId,
        $text
        );
    }


}

?>