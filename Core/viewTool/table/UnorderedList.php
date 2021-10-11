<?php 

namespace app\Core\viewTool\table;

class UnorderedList
{
    public static function begin()
    {
        echo '<div class="row">';
        echo '<div class="col-10">';
        echo '<ul class="list-group">';
        return new UnorderedList();
    }

    public static function end()
    {
        echo '</ul>';
        echo '</div>';
        echo '</div>';
    }

    //
    public function li($url, $model, $attribute, $button)
    {
        return new ListItem($url, $model, $attribute, $button);
    }

    public function button()
    {

    }
}
?>