<?php 

namespace app\Core\viewTool\cardField;

class CardField
{

    public static function begin(int $cardsOnRow)
    {
        echo '
            <div class="album py-5 bg-light">
            <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-' . $cardsOnRow .' g-3">
        ';

        return new CardField();
    }

    public static function end()
    {
        echo '
            </div>
            </div>
            </div>
        ';
    }

    public function Card($model)
    {
        return new Card($model);
    }
}

?>