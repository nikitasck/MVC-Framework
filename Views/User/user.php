<div class="container">
    <div class="row">

      <div class="col-md-2">
        <p><?php echo $model->firstname . ' ' . $model->lastname ?></p>
      </div>
      <div class="col-md-1">
        <div class="img ratio ratio-1x1">
          <img class="img-fluid img-thumbnail" src="<?php echo $model->src ?>" alt="">
        </div>
      </div>
</div>
  
<?php if(empty($userArticles)): ?>
<div class="text-center bg-light"> <h4>This user doesn't have articles.</h4> </div>
<?php else: ?>

<h3>Latest Articles:</h3>

<?php
  $cardField = app\Core\viewTool\cardField\CardField::begin(4);

  foreach($userArticles as $value){
    echo $cardField->Card($value);
  } 
  
  $cardField->end(); 
  
?>

<nav aria-label="Page navigation example">
  <ul class="pagination justify-content-center mt-3">
    <?php foreach($pagArr as $key => $value): ?>
    <li class="page-item"><a class="page-link" href="?page=<?php echo $key ?>"><?php echo $key ?></a></li>
    <?php endforeach; ?>
  </ul>
</nav>

<?php endif; ?>


