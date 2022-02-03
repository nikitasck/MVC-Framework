<!-- Page Content -->
<div class="container">

    <!-- Portfolio Item Heading -->
    <!-- Portfolio Item Row -->
    <div class="row">
  
      <div class="col-md-1">

            <p><?php

use app\Core\Application;

echo $model->firstname . ' ' . $model->lastname ?></p>

      </div>

      <div class="col-md-1">
        <div class="img ratio ratio-1x1">
          <img class="img-fluid img-thumbnail" src="<?php echo $model->src ?>" alt="">
        </div>
      </div>
      <div class="col-md-2 mt-1">
        <a href="/edit/user/<?php echo Application::$app->session->get('user') ?>" class="align-middle border border-secondary rounded link-dark text-decoration-none p-2">Edit Profile</a>
      </div>
</div>
    <!-- /.row -->
  
    <!-- Related Projects Row -->
<h3>articles:</h3>
  
<?php if(empty($userArticles)): ?>
<div class="text-center bg-light"> <h4>This user doesn't have articles.</h4> </div>
<?php else: ?>

  <ul class="list-group">
        <?php foreach($userArticles as $key => $value): ?>
                <li class="list-group-item justify-content-between">
                <a class="text-decoration-none link-dark" href="../article/<?php echo $value->id ?>">
                    <div class="row row-cols-2">
                        <div class="col-8">
                            <div class="row">
                                <div class="col-8 text-center">
                                    <b>Title:</b> <?php echo $value->title ?>
                                </div>
                                <div class="col-2">
                                    <div class="img ratio ratio-1x1">
                                        <img class="img-fluid" src="<?php echo $value->src ?>" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                        <div class="col-6 text-end">
                            <a href="/edit/article/<?php echo$value->id ?>" class="border bg-light rounded link-dark text-decoration-none p-2">Edit</a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $value->id ?>">
                                Delete
                            </button>

                            <!-- modal -->
                            <div class="modal fade" id="exampleModal<?php echo $value->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Delete article:  <?php echo $value->title ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    Are you sure?
                                </div>
                                <div class="modal-footer">
                                    <a href="/delete/article/<?php echo $value->id ?>" class="border bg-light rounded link-dark text-decoration-none p-2">Delete</a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </li>
        <?php endforeach; ?>
    </ul>


    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center mt-3">
            <?php foreach($pagArr as $key => $value): ?>
            <li class="page-item"><a class="page-link" href="users?page=<?php echo $key ?>"><?php echo $key ?></a></li>
            <?php endforeach; ?>
        </ul>
    </nav>
<?php endif; ?>

