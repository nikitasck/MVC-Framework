<?php 

namespace app\Models;

use app\Core\Application;
use app\Core\Model;

class LoginForm extends Model
{
    public $email;
    public $password;

    public function rules(): array
    {
        return [
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8]]
        ];
    }

    public function labels(): array
    {
        return [
            'email' => 'Enter email',
            'password' => 'Enter password'
        ];
    }

    public function login()
    {
        $user = (new User())->findOne(['email' => $this->email]);
        //$user->findOne($this->email); ---Протестировать
        //$user->findOne(['email' => $this->email]);

        if(!$user) {
            $this->addError('email', 'User with this email doesnt exists ');
            return false;
        }

        if(!password_verify($this->password, $user->password)) {
            $this->addError('password', 'Password is incorect');
            return false;
        }

        
        return Application::$app->login($user);
    }
}

?>