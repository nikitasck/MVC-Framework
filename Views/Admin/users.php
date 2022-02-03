
<div class="container">
    <h1>Users</h1>

    <?php //echo var_dump($users) ?>
    
    <ul class="list-group">
        <?php foreach($users as $key => $value): ?>
                <li class="list-group-item justify-content-between">
                <a class="text-decoration-none link-dark" href="../user/<?php echo $value->id ?>">
                    <div class="row row-cols-2">
                        <div class="col-8">
                            <div class="row">
                                <div class="col-8 text-center">
                                    <?php echo $value->firstname . ' ' . $value->lastname ?>
                                </div>
                                <div class="col-4">
                                    <div class="img ratio ratio-1x1">
                                        <img class="img-fluid" src="<?php echo $value->src ?>" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                        <div class="col-6 text-end">
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $value->id ?>">
                                Delete
                            </button>

                            <!-- modal -->
                            <div class="modal fade" id="exampleModal<?php echo $value->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Delete user:  <?php echo $value->firstname . ' ' . $value->lastname ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    Are you sure?
                                </div>
                                <div class="modal-footer">
                                    <a href="/delete/user/<?php echo $value->id ?>" class="border bg-light rounded link-dark text-decoration-none p-2">Delete</a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
</div>

