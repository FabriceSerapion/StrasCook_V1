<?php

namespace App\Model;

use PDO;

class TagManager extends AbstractManager
{
    public const TABLE = 'tag';
    public const TABLEJOIN = 'menu_tag';

    public const PATH = 'Tag.html.twig';

    /**
     * Get all tags for one menu from database by ID.
     */
    public function selectAllTagsFromMenu(int $id): array|false
    {
        $statement = $this->pdo->prepare(
            "SELECT tag.id,tag.name_tag FROM " . self::TABLE . " 
        INNER JOIN menu_tag ON tag.id = menu_tag.id_tag 
        INNER JOIN menu on menu_tag.id_menu = menu.id 
        WHERE menu.id=:id"
        );
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }

    /**
     * Get one id tag from its name from database by ID.
     */
    public function selectTagFromName(string $newTag): array|false
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT id FROM " . self::TABLE . " WHERE name_tag = :newTag");
        $statement->bindValue('newTag', $newTag, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch();
    }

    /**
     * Insert new tag in database
     */
    public function insert(array $tag): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`name_tag`) VALUES (:name_tag)");
        $statement->bindValue('name_tag', $tag['name_tag'], PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update name tag in database
     */
    public function update(array $tag): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `name_tag` = :name_tag 
        WHERE id=:id");
        $statement->bindValue('id', $tag['id'], PDO::PARAM_INT);
        $statement->bindValue('name_tag', $tag['name_tag'], PDO::PARAM_STR);

        return $statement->execute();
    }

    /**
     * Overwritting delete method in abstract manager --> deleting lines in table join in more
     */
    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLEJOIN . " WHERE id_tag=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * Validation method specific for this item
     */
    public function validation(array $item): array
    {
        $errors = array();
        if (empty($item['name_tag'])) {
            $errors[] = "Le nom du tag est nécessaire !";
        }
        return $errors;
    }
}
