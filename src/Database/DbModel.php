<?php

namespace Mohamed179\Core\Database;

use Mohamed179\Core\Application;
use Mohamed179\Core\Model;

abstract class DbModel extends Model
{
    protected static string $tableName = '';
    protected static array $attributes = [];

    public int $id = 0;

    public static function getTableName(): string
    {
        if (!empty(static::$tableName)) {
            return static::$tableName;
        }

        $pattern = '!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!';
        preg_match_all($pattern, static::class, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ?
                strtolower($match) :
                lcfirst($match);
        }
        static::$tableName = implode('_', $ret);
        return static::$tableName;
    }

    protected static function sqlSetString(): string
    {
        return implode(', ', array_map(function ($attribute) {
            return "$attribute = :$attribute";
        }, static::$attributes));
    }

    protected static function sqlWhereSetString(array $attributes): string
    {
        return implode(', ', array_map(function ($attribute) {
            return "$attribute = :$attribute";
        }, $attributes));
    }

    public static function find(int $id)
    {
        $tableName = static::getTableName();
        $sql = sprintf("SELECT * FROM %s WHERE id = :id;", $tableName);
        $db = Application::$app->db;
        $db->prepare($sql);
        if ($db->execute(['id' => $id])) {
            return $db->fetchObject(static::class);
        } else {
            return null;
        }
    }

    public static function where(array $data)
    {
        $tableName = self::getTableName();
        $sql = sprintf(
            "SELECT * FROM %s WHERE %s;",
            $tableName,
            self::sqlWhereSetString(array_keys($data))
        );
        $db = Application::$app->db;
        $db->prepare($sql);
        if ($db->execute($data)) {
            return $db->fetchObject(static::class);
        } else {
            return null;
        }
    }

    public static function create(array $data)
    {
        $sql = sprintf("INSERT INTO %s SET %s;", self::getTableName(), self::sqlSetString());
        $db = Application::$app->db;
        $db->prepare($sql);
        if ($db->execute($data)) {
            return intval($db->lastInsertId());
        } else {
            return false;
        }
    }

    public function update(): bool
    {
        $sql = sprintf("UPDATE %s SET %s WHERE id = :id", self::getTableName(), self::sqlSetString());
        $db = Application::$app->db;
        $db->prepare($sql);
        foreach (static::$attributes as $attribute) {
            $db->bindParam($attribute, $this->{$attribute});
        }
        $db->bindParam("id", $this->id);
        return $db->execute();
    }

    public function save(): bool
    {
        if ($this->id === 0) {
            $data = [];
            foreach (static::$attributes as $attribute) {
                $data[$attribute] = $this->{$attribute};
            }
            $calssName = static::class;
            $id = $calssName::create($data);
            if ($id !== false) {
                $this->id = $id;
                return true;
            } else {
                return false;
            }
        }

        return $this->update();
    }
}
