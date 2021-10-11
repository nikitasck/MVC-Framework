<h3>Articles</h3>
<?php if(empty($articles)): ?>
<div class="text-center bg-light"> <h4>Not articles yet.</h4> </div>
<?php else: ?>

<?php
  $cardField = app\Core\viewTool\cardField\CardField::begin(4);

  foreach($articles as $value){
    echo $cardField->Card($value);
  } 
  
  $cardField->end() 
?>
<nav aria-label="Page navigation example">
  <ul class="pagination justify-content-center mt-3">
    <?php foreach($pagArr as $key => $value): ?>
    <li class="page-item"><a class="page-link" href="articles?page=<?php echo $key ?>"><?php echo $key ?></a></li>
    <?php endforeach; ?>
  </ul>
</nav>
<?php endif; ?>

