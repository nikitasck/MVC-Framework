<?php

namespace app\Core;
use app\Core\Application;

/*
Класс для работы с моделями базы данных.
*/

abstract class DbModel extends Model
{
    //return name of table in database
    abstract public function tableName(): string;
    //return primaryKey in database for this model instance
    abstract public function primaryKey(): string;
    //return array[string] names of model attributes
    abstract public function attributes(): array;
    //return labels for attributes, example: arrar['firstName' => 'First Name']
    abstract public function labels(): array;

    public function save()
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();

        //Приводим атрибуты к виду благоприятному для подстанивки в запросе.
        $params = array_map(fn($attr) => ":$attr", $attributes);

        $sql = "INSERT INTO $tableName (" . implode(',', $attributes) . ") VALUES (" . implode(',', $params) . ")";

        $statement = self::prepare($sql);

        foreach($attributes as $attribute) {
            $statement->bindParam(":$attribute", $this->{$attribute});
        }

        $statement->execute();
        return true;

    }

    public function findOne($where)
    {
        $tableName = $this->tableName();
        $attribute = array_keys($where);

        $params = implode('AND', array_map(fn($attr) => "$attr = :$attr", $attribute));

        $sql = "SELECT * FROM $tableName WHERE $params";

        $statement = self::prepare($sql);

        foreach($where as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        $statement->execute();

        //Возвращает сущность класса(класса, в котором вызывается данный метод) с свойствами(атрибутами) соответствующим с столбцами таблицы.
        return $statement->fetchObject(self::class);
    }

    public static function prepare($sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }
}