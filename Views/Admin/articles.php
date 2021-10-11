
<div class="container">
    <h1>Articles</h1>

    <?php $table = \app\Core\viewTool\table\UnorderedList::begin() ?>
    <?php 
    foreach ($model as $key => $value){
        echo $table->li('articles/article/', $key, $value,  ['/edit' => 'Edit', '/delit' => 'Delete']);
    } 
    ?>
    <?php echo $table->end() ?>

