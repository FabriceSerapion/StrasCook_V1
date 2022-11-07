<?php

namespace App\Model;

use PDO;

final class UserManager extends AbstractManager
{
    public const TABLE = 'user';

    /**
     * Insert new item in database
     */
    public function insert(array $user): int
    {
        // TODO create $username $password
        $statement = $this->pdo->prepare(
            "INSERT INTO " . self::TABLE . " (`username`, `password`) 
            VALUES (:username, :password)"
        );
        // $statement->bindValue('username', $user['username'], PDO::PARAM_STR);
        // $statement->bindValue('password', $user['password'], PDO::PARAM_STR);

        // $statement->execute();
        $statement->execute([
            ':username' => $user['username'],
            ':password' => $user['password']
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function selectOneByUsername(string $username): array|false
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM " . self::TABLE . " WHERE username=:username");
        $statement->bindValue('username', $username, \PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetch();
    }
}
