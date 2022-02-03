
<?php $form = app\Core\viewTool\form\Form::begin('', 'post', 'multipart/form-data') ?>
<h3 class="h3 mb-3 fw-normal">Update article</h3>
<?php
echo $form->field($model, 'title');
echo $form->textArea($model, 'text');
echo $form->field($img, 'src')->imageField();
echo $form->button('Update')->submitButton();  
echo $form->end() ;
?>
