<?php $form = app\Core\viewTool\form\Form::begin('', 'post', 'multipart/form-data') ?>
<h3 class="h3 mb-3 fw-normal">Update user</h3>
<?php
echo $form->field($model, 'firstname');
echo $form->field($model, 'lastname');
echo $form->field($img, 'src')->imageField();
echo $form->button('Update')->submitButton();  
echo $form->end();
?>