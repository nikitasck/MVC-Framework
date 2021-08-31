<?php

namespace app\Core;

class Session
{
   protected const FLASH_KEY = 'flash_messages';

    public function __construct()
    {
        session_start();

        //Регистрируем переменные в сессии. Устанавливаем ключ для удаления.
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach($this->flashMessages as $key => &$flashMessage) {
            $flashMessage['remove'] = true;
        }
        
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

    public function setFlash($key, $message)
    {
        $_SESSION[self::FLASH_KEY][$key] = [
            'remove' => false,
            'value' => $message
        ];
    }

    public function getFlash($key)
    {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        return $_SESSION[$key];
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public function __destruct()
    {
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach($this->flashMessages as $key => &$flashMessage) { //Для изменения состояния, используем указатель на исходный обьект
            if($flashMessage['remove'] === true) {
                unset($flashMessages[$key]);
            }
        }

        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }
}

?>