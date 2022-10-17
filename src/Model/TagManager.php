<?php

namespace App\Model;

use PDO;

class TagManager extends AbstractManager
{
    public const TABLE = 'tag';

    /**
     * Insert new tag in database
     */
    public function insertTag(array $tag): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`name_tag`) VALUES (:name_tag)");
        $statement->bindValue('name_tag', $tag['name_tag'], PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update tag in database
     */
    public function updateNameTag(array $tag): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `name_tag` = :name_tag WHERE id_tag=:id_tag");
        $statement->bindValue('id_tag', $tag['id_tag'], PDO::PARAM_INT);
        $statement->bindValue('name_tag', $tag['name_tag'], PDO::PARAM_STR);

        return $statement->execute();
    }

        /**
     * Get one row from database by ID.
     */
    public function selectOneTagById(int $id): array|false
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM " . self::TABLE . " WHERE id_tag=:id_tag");
        $statement->bindValue('id_tag', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    /**
     * Delete row form an ID
     */
    public function deleteTag(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id_tag=:id_tag");
        $statement->bindValue('id_tag', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}
