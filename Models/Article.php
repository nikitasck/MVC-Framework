<?php 

namespace app\Models;

use app\Core\DbModel;

class Article extends DbModel
{
    public $title = "";
    public $text = "";
    public $user_id = "";
    public $img_id = "";

    public function tableName(): string
    {
        return 'articles';
    }

    public function primaryKey(): string
    {
        return 'id';
    }
    
    public function attributes(): array
    {
        return ['title', 'text', 'user_id', 'img_id'];
    }

    public function rules(): array
    {
        return [
            'title' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => '6']],
            'text' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => '6']],
        ];
    }

    public function labels(): array
    {
        return [
            'title' => 'Article title',
            'text' => 'What do you want to write'
        ];
    }

    public function getOneArticle($id)
    {
        $tableName = $this->tableName();

        $sql = "SELECT * FROM $tableName WHERE id = :id";

        $statement = self::prepare($sql);
        $statement->bindParam(":id", $id);
        $statement->execute();
        return $statement->fetchObject(self::class);
    }

    //Return array of articles with src from image table. Can return needed count of rows.
    public function getArticlesForCards(array $limit, $imgTable)
    {
        $limit = implode(',', $limit);
        $tableName = $this->tableName();
        $sql = "SELECT $tableName.id, $tableName.title, $tableName.created_at, $imgTable.src FROM $tableName LEFT JOIN $imgTable ON $tableName.img_id = $imgTable.id ORDER BY $tableName.id DESC LIMIT $limit";
        $statement = self::prepare($sql);
        $statement->execute();
        return $statement->fetchALL(\PDO::FETCH_OBJ);
    }

    public function getUserArticlesForCards(array $limit,$userId, $imgTable)
    {
        $limit = implode(',', $limit);
        $tableName = $this->tableName();
        $sql = "SELECT $tableName.id, $tableName.title, $tableName.created_at, $imgTable.src FROM $tableName LEFT JOIN $imgTable ON $tableName.img_id = $imgTable.id WHERE $tableName.user_id = :id ORDER BY $tableName.id DESC LIMIT $limit";
        $statement = self::prepare($sql);
        $statement->bindParam(":id", $userId);
        $statement->execute();
        return $statement->fetchALL(\PDO::FETCH_OBJ);
    }

    public function getArticleObjForPage($id, $imgTable, $userTable)
    {
        $tableName = $this->tableName();
        $sql = "SELECT $tableName.id, $tableName.title, $tableName.text, $tableName.user_id, $tableName.created_at, $userTable.firstname, $userTable.lastname, $imgTable.src FROM $tableName LEFT JOIN $imgTable ON $tableName.img_id = $imgTable.id LEFT JOIN $userTable ON $tableName.user_id = $userTable.id WHERE $tableName.id = :id";
        $statement = self::prepare($sql);
        $statement->bindParam(":id", $id);
        $statement->execute();
        return $statement->fetch(\PDO::FETCH_OBJ);
    }

    //Return count of user 
    public function getCountOfUserArticles($userId)
    {
        $tableName = $this->tableName();
        $sql = "SELECT COUNT(*) FROM $tableName WHERE $tableName.user_id = :user_id";

        $statement = self::prepare($sql);
        $statement->bindParam(":user_id", $userId);
        $statement->execute();
        return $statement->fetchColumn();

    }

    public function getUserArticlesForList($limit, $userId, $imgTable)
    {
        $tableName = $this->tableName();
        $limit = implode(',', $limit);

        $sql = "SELECT $tableName.id, $tableName.title, $imgTable.src FROM $tableName LEFT JOIN $imgTable ON $tableName.img_id = $imgTable.id WHERE $tableName.user_id = :id ORDER BY $tableName.id DESC LIMIT $limit";
        $statement = self::prepare($sql);
        $statement->bindParam(":id", $userId);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getArticlesForList($limit, $imgTable)
    {
        $tableName = $this->tableName();
        $limit = implode(',', $limit);

        $sql = "SELECT $tableName.id, $tableName.title, $imgTable.src FROM $tableName LEFT JOIN $imgTable ON $tableName.img_id = $imgTable.id LIMIT $limit";
        $statement = self::prepare($sql);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_OBJ);
    }

}

?>
