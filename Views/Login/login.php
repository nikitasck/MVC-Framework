<?php $form = app\Core\viewTool\form\Form::begin('', 'post') ?>
<h1>Login</h1>
<?php echo $form->field($model, 'email') ?>
<?php echo $form->field($model, 'password')->passwordField() ?>

<p> New here? You need <a href="/register">registrate</a> first and then login.</p>

<button type="submit" class="btn btn-primary">Submit</button>
<?php echo $form->end() ?>