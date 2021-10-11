<!-- Page Content -->
<div class="container">

    <!-- Portfolio Item Heading -->
    <h1><?php echo $model->title ?></h1>
    <small class = "p-1">writer: <a href="/user/<?php echo $model->user_id ?>" class = "text-decoration-none"><?php echo $model->firstname . ' ' . $model->lastname ?></a></small>
    <!-- Portfolio Item Row -->
    <div class="row">
  
      <div class="col-md-6">
          <div class="img ratio ratio-1x1">
            <img class="img-fluid img-thumbnail" src="<?php echo $model->src ?>" alt="">
          </div>

      </div>

      <div class="col-md-6 bg-light">
        <p><?php echo $model->text ?></p>
      </div>

        <div class="row justify-content-end">
            <div class="col-md-3">
                <small>added: <?php echo $model->created_at ?></small>
            </div>
        </div>
</div>
    <!-- /.row -->
  
    <!-- Related Projects Row -->
    <h3>Latest Articles:</h3>
  
    <div class="row">
  
    <?php if(empty($articleCards)): ?>
<div class="text-center bg-light"> <h4>Not articles yet.</h4> </div>
<?php else: ?>

<?php
  $cardField = app\Core\viewTool\cardField\CardField::begin(3);

  foreach($articleCards as $value){
    echo $cardField->Card($value);
  } 
  
  $cardField->end() 
?>
<?php endif; ?>
  
    </div>
    <!-- /.row -->
  
  </div>
  <!-- /.container -->