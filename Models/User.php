<?php

namespace app\Models;
use app\Core\UserModel;

class User extends UserModel 
{
    protected $firstName;
    protected $lastName;
    protected $email;
    protected $password;
    protected $confirmPassword;

    public function rules(): array
    {
        return [
            'firstName' => [self::RULE_REQUIRED],
            'lastname' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, self::RULE_UNIQUE],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8]],
            'confirmPassword' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']]
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
            'firstName',
            'lastName',
            'email',
            'password',
            'confirmPassword'
        ];
    }

    public function labels(): array
    {
        return [
            'firstName' => 'First Name',
            'lastName' => 'Second Name',
            'email' => 'Email',
            'password' => 'Password',
            'confirmPassword' => 'Confirm Password'
        ];
    }

    public function getDisplayName(): string
    {
        return $this->firstName . " " . $this->lastName;
    }

    //Переопределем радительский метод сохранения модели. Хешируем пароль с использыванием соли.
    public function save()
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::save();
    }
    
}

?>