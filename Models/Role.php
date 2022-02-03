<?php 

namespace app\Models;

use app\Core\DbModel;

class Role extends DbModel
{
    protected const ROLE_ADMIN = "admin";
    protected const ROLE_MODERATOR = "moderator";

    public $user_id = "";
    public $role = "";

    public function tableName(): string
    {
        return 'roles';
    }

    public function primaryKey(): string
    {
        return 'id';
    }

    public function attributes(): array
    {
        return ['user_id', 'role'];
    }

    public function rules(): array
    {
        return [
            'user_id' => [self::RULE_REQUIRED, [self::RULE_UNIQUE, 'class' => self::class]],
            'role' => [self::RULE_REQUIRED]
        ];
    }

    public function labels(): array
    {
        return [
            'user_id' => 'User',
            'role' => 'Role'
        ];
    }
    //Getting user role.
    public function hasRole($userKey)
    {
        $tableName = $this->tableName();
        $sql = "SELECT * FROM $tableName WHERE user_id = :user_id";
        $statement = self::prepare($sql);
        $statement->bindParam(":user_id", $userKey);
        $statement->execute();

        $usrObj = $statement->fetchObject();

        if($usrObj) {
            return $usrObj;
        }
    }

    //Getting user permissions.
    public function premission($usrId)
    {
        $role = $this->hasRole($usrId);

        if($role){
            if($role->role === self::ROLE_ADMIN){
                return self::ROLE_ADMIN;
            } else if ($role->role === self::ROLE_MODERATOR) {
                return self::ROLE_MODERATOR;
            }
        } else {
            return false;
        }
    }
}

?>
