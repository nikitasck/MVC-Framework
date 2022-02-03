
<?php $form = app\Core\viewTool\form\Form::begin('', 'post', 'multipart/form-data') ?>
<h1>Registration</h1>
<?php echo $form->field($model, 'firstname') ?>
<?php echo $form->field($model, 'lastname') ?>
<?php echo $form->field($model, 'email') ?>
<?php echo $form->field($model, 'password')->passwordField() ?>
<?php echo $form->field($model, 'confirmPassword')->passwordField()?>

<p> Already have account? Click <a href="/register">here</a> to login.</p>
<?php //echo $form->button('Register')->submitButton(); ?>
<button type="submit" class="btn btn-primary">Submit</button>
<?php echo $form->end() ?>