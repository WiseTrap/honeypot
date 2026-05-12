<?php

namespace WiseTrap\App\Models;

use InvalidArgumentException;
use PDO;
use PDOException;
use WiseTrap\Core\Application;

abstract class DbModel extends Model
{
    abstract public static  function tableName(): string;
    abstract public function attributes(): array;
    abstract public static function primaryKey(): string;
    public static function findOne(array $where)
    {
        $tableName = static::tableName();

        if (empty($where)) {
            throw new InvalidArgumentException("The 'where' array cannot be empty.");
        }

        $conditions = implode(" AND ", array_map(fn($key) => "$key = :$key", array_keys($where)));

        $sql = "SELECT * FROM $tableName WHERE $conditions LIMIT 1";
        $statement = Application::$app->database->prepare($sql);

        foreach ($where as $key => $value) {
            $paramType = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $statement->bindValue(":$key", $value, $paramType);
        }

        try {
            $statement->execute();
            return $statement->fetchObject(static::class) ?: null;
        } catch (PDOException $e) {
            error_log("Database error in findOne(): " . $e->getMessage());
            return null;
        }
    }
}