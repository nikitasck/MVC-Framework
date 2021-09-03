<?php 

namespace app\Models;

use app\Core\DbModel;
use app\Core\Application;

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
    //Узнаем, какую роль имеем
    public function hasRole($userKey)
    {
        $tableName = $this->tableName();
        $sql = "SELECT * FROM $tableName WHERE user_id = :user_id";
        $statement = self::prepare($sql);
        $statement->bindParam(":user_id", $userKey);
        $statement->execute();

        $usrObj = $statement->fetchObject(static::class);

        if($usrObj) {
            return $usrObj;
        }
    }

    //Получение установка прав в апликуху
    public function premission()
    {
        //Получаем пользователя
        $usrPrimKey = Application::$app->session->get('user');
        $user = (new User())->findOne(['id' => $usrPrimKey]);

        if(!$user) {
            $this->addError('user', 'User doesnt exists');
            return false;//Переписать!
        }

        $role = $this->hasRole($usrPrimKey);

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
