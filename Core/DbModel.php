<?php

namespace app\Core;
use app\Core\Application;

abstract class DbModel extends Model
{
    //Get db table name of model.
    abstract public function tableName(): string;
    //Get db primary key of model.
    abstract public function primaryKey(): string;
    //Get model attributes array
    abstract public function attributes(): array;

    public function save()
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();

        //Preparing every attribute for bind.
        $params = array_map(fn($attr) => ":$attr", $attributes);

        $sql = "INSERT INTO $tableName (" . implode(',', $attributes) . ") VALUES(" . implode(',', $params) . ")";

        $statement = self::prepare($sql);

        foreach($attributes as $attribute) {
            $statement->bindParam(":$attribute", $this->{$attribute});
        }

        $statement->execute();
        return true;

    }

    //Return objects of table where searching. Passed array with finding params.
    public function findOne(array $where)
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

        return $statement->fetchObject(static::class);
    }

    public function getRowsCount()
    {
        $tableName = $this->tableName();
        $sql = "SELECT COUNT(*) FROM $tableName";

        $statement = self::prepare($sql);
        $statement->execute();

        return $statement->fetchColumn();
    }

    public function deleteRow($id)
    {
        $tableName = $this->tableName();

        $sql = "DELETE FROM $tableName WHERE id = :id";

        $statement = self::prepare($sql);
        $statement->bindParam(':id', $id);
        return $statement->execute();
        
    }

    //Prepare query.
    public static function prepare($sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }

    //Get last inserted id.
    public static function lastInsertId()
    {
        return Application::$app->db->pdo->lastInsertId();
    }

    //Update only filled attributes in model.
    public function updateFilledAttributesForRow($id)
    {
        $tableName = $this->tableName();

        //Saves the names of the filled attributes.
        foreach($this->attributes() as $attribute){
            if(!empty($this->{$attribute})){
                $attributeNames[] = $attribute;
            }
        }

        //Preparing filled attributes for bind.
        $params = array_map(fn($attr)=>"$attr = :$attr", $attributeNames);

        $sql = "UPDATE $tableName SET " . implode(',', $params) . " WHERE id = :id";
        $statement = self::prepare($sql);

        foreach($attributeNames as $attribute) {
            $statement->bindParam(":$attribute", $this->{$attribute});
        }
        $statement->bindParam(":id", $id);

        $statement->execute();

        return true;
    }
}