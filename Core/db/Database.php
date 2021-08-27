<?php

namespace app\Core\db;

use PDOException;

class Database
{
    private \PDO $pdo;

    public function __construct(array $config) 
    {   
        $serverName = $config['serverName'];
        $dbName = $config['dbName'];
        $userNameDB = $config['userNameDB'];
        $password = $config['password'];

        //connection to db
        try {
            $this->pdo = new \PDO("mysql:host=$serverName;dbname=$dbName", $userNameDB, $password);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function prepare($sql)
    {
        $this->pdo->prepare($sql);
    }
}


?>