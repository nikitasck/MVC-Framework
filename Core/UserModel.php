<?php

namespace app\Core;
use app\Core\DbModel;

abstract class UserModel extends DbModel
{
   abstract public function getDisplayName(): string;
}

?>