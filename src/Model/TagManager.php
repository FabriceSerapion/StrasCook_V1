<?php

namespace App\Model;

use PDO;

class TagManager extends AbstractManager
{
    public const TABLE = 'tag';

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
}
