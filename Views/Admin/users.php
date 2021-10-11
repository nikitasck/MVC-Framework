
<div class="container">
    <h1>Users</h1>

    <?php echo var_dump($users) ?>

    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center mt-3">
            <?php foreach($pagArr as $key => $value): ?>
            <li class="page-item"><a class="page-link" href="users?page=<?php echo $key ?>"><?php echo $key ?></a></li>
            <?php endforeach; ?>
        </ul>
    </nav>
</div>

