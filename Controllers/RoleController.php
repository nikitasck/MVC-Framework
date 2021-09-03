<?php 

namespace app\Controllers;

use app\Core\Controller;
use app\Models\Role;
use app\Core\exception\ForbiddenExcepention;

class RoleController extends Controller
{

    public function adminPanel()
    {
        $model = new Role();
        $result = $model->premission();

        if($result === 'admin') {
            return $this->render('admin', [
                "model" => 'Admin panel'
            ]);
        } else if($result === 'moderator') {
            return $this->render('moderator', [
                "model" => 'moderator panel'
            ]);
        } else {
            throw new ForbiddenExcepention();
        }
    }

    public function moderatorPanel()
    {
        $model = new Role();
        $result = $model->premission();

        if($result === 'moderator') {
            return $this->render('moderator', [
                "model" => 'moderator panel'
            ]);
        } else {
            throw new ForbiddenExcepention();
        }
    }

}

?>