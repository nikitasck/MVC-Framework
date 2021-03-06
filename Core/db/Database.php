<?php

namespace app\Core\db;

use PDOException;

/*
Creating instance of the PDO class.
*/

class Database
{
    public \PDO $pdo;

    public function __construct(array $config) 
    {   
        $dsn = $config['dsn'] ?? '';
        $db_user = $config['db_user'] ?? '';
        $db_password = $config['db_password'] ?? '';

        try {
            $this->pdo = new \PDO($dsn,$db_user,$db_password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error in database:" . $e->getMessage();
        }
    }

    //Preparing sql request.
    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }
}


?>