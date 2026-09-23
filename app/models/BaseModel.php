<?php
declare(strict_types=1);

namespace App\Models;

use App\Helpers\Database;
use PDO;
use PDOStatement;

abstract class BaseModel
{
    protected static function db(): PDO
    {
        return Database::getConnection();
    }

    public static function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = self::db()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetch(string $sql, array $params = []): ?array
    {
        $stmt = self::query($sql, $params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public static function fetchAll(string $sql, array $params = []): array
    {
        $stmt = self::query($sql, $params);
        return $stmt->fetchAll();
    }

    public static function fetchColumn(string $sql, array $params = []): mixed
    {
        $stmt = self::query($sql, $params);
        return $stmt->fetchColumn();
    }

    public static function insert(string $table, array $data): int
    {
        $fields = array_keys($data);
        $placeholders = array_map(fn($f) => ":{$f}", $fields);

        $sql = sprintf(
            'INSERT INTO `%s` (%s) VALUES (%s)',
            $table,
            implode(', ', array_map(fn($f) => "`{$f}`", $fields)),
            implode(', ', $placeholders)
        );

        $params = [];
        foreach ($data as $key => $val) {
            $params[":{$key}"] = $val;
        }

        self::query($sql, $params);
        return (int) self::db()->lastInsertId();
    }

    public static function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $setClauses = [];
        $params = [];

        foreach ($data as $key => $val) {
            $setClauses[] = "`{$key}` = :set_{$key}";
            $params[":set_{$key}"] = $val;
        }

        $sql = sprintf(
            'UPDATE `%s` SET %s WHERE %s',
            $table,
            implode(', ', $setClauses),
            $where
        );

        foreach ($whereParams as $key => $val) {
            $placeholder = str_starts_with($key, ':') ? $key : ":{$key}";
            $params[$placeholder] = $val;
        }

        $stmt = self::query($sql, $params);
        return $stmt->rowCount();
    }

    public static function delete(string $table, string $where, array $whereParams = []): int
    {
        $sql = sprintf('DELETE FROM `%s` WHERE %s', $table, $where);
        $params = [];
        foreach ($whereParams as $key => $val) {
            $placeholder = str_starts_with($key, ':') ? $key : ":{$key}";
            $params[$placeholder] = $val;
        }

        $stmt = self::query($sql, $params);
        return $stmt->rowCount();
    }

    public static function count(string $table, string $where = '', array $params = []): int
    {
        $sql = "SELECT COUNT(*) FROM `{$table}`";
        if (!empty($where)) {
            $sql .= " WHERE {$where}";
        }

        return (int) self::fetchColumn($sql, $params);
    }
}
