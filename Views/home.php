<?php

use app\Core\Application;

$title = 'Home'; ?>
<?php

header("Content-Type:doggie.png");
?>

<section class="py-5 text-center container">
  <div class="row py-lg-5">
    <div class="col-lg-6 col-md-8 mx-auto">
      <h1 class="fw-light">Welcome to blog!</h1>
      <p class="lead text-muted">There is you can read, write articles. Sign-in to write articles.</p>
    </div>
  </div>
</section>
<?php if(empty($model)): ?>
<div class="text-center bg-light"> <h4>Not articles yet.</h4> </div>
<?php else: ?>

<?php
  $cardField = app\Core\viewTool\cardField\CardField::begin(3);

  foreach($model as $value){
    echo $cardField->Card($value);
  } 
  
?>

<?php $cardField->end()  ?>
<a href="/articles" class="text-decoration-none text-dark"><button type="button" class="btn btn-info w-100 rounded-0">More articles</button></a>
<?php endif; ?>