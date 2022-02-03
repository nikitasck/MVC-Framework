<?php

namespace app\Models;
use app\Core\UserModel;

class User extends UserModel 
{
    public string $firstname = "";
    public string $lastname = "";
    public string $email = "";
    public string $password = "";
    public string $confirmPassword = "";
    public string $img_id = "";

    public function rules(): array
    {
        return [
            'firstname' => [self::RULE_REQUIRED],
            'lastname' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [self::RULE_UNIQUE, 'class' => self::class ]],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8]],
            'confirmPassword' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']],
        ];
    }
    

    public function tableName():string
    {
        return 'users';
    }

    public function primaryKey(): string
    {
        return 'id';
    }

    public function attributes(): array
    {
        return [
            'firstname',
            'lastname',
            'email',
            'password',
            'img_id'
        ];
    }

    public function labels(): array
    {
        return [
            'firstname' => 'First Name',
            'lastname' => 'Second Name',
            'email' => 'Email',
            'password' => 'Password',
            'confirmPassword' => 'Confirm Password'
        ];
    }

    public function getDisplayName(): string
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    //Extending parent method. Hashing password
    public function save()
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::save();
    }

    //Retrieving the specified user from database.
    public function getOneUser($id)
    {
        $tableName = $this->tableName();

        $sql = "SELECT * FROM $tableName WHERE id = :id";

        $statement = self::prepare($sql);
        $statement->bindParam(":id", $id);
        $statement->execute();
        return $statement->fetchObject(self::class);
    }

    //Return object that contain user info and src to him picture.
    public function getUserProfile($id, $imgTable)
    {
        $tableName = $this->tableName();

        $sql = "SELECT $tableName.id, $tableName.firstname, $tableName.lastname, $imgTable.src FROM $tableName LEFT JOIN $imgTable ON $tableName.img_id = $imgTable.id WHERE $tableName.id = :id";
        $statement = self::prepare($sql);
        $statement->bindParam(":id", $id);
        $statement->execute();
        return $statement->fetch(\PDO::FETCH_OBJ);
    }

    public function getUsersForList($limit, $imgTable)
    {
        $tableName = $this->tableName();
        $limit = implode(',', $limit);

        $sql = "SELECT $tableName.id, $tableName.firstname, $tableName.lastname, $imgTable.src FROM $tableName LEFT JOIN $imgTable ON $tableName.img_id = $imgTable.id LIMIT $limit";
        $statement = self::prepare($sql);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_OBJ);
    }
    
    public function findEmail($email)
    {
        $tableName = $this->tableName();
        $sql = "SELECT * FROM $tableName WHERE email = :email";

        $statement = self::prepare($sql);
        $statement->bindParam(':email', $email);
        $statement->execute();
        return $statement->fetchObject(self::class);
    }

    public function updateUser($id)
    {
        
    }
}

?>