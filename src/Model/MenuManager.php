<?php

namespace App\Model;

use PDO;

class MenuManager extends AbstractManager
{
    public const TABLE = 'menu';
    public const TABLEJOIN = 'tag_menu';

    /**
     * Insert new menu in database
     */
    public function insertMenu(array $menu): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`name_menu`, `price_menu`, 
        `description_menu`) VALUES (:name_menu, :price_menu, :description_menu)");
        $statement->bindValue('name_menu', $menu['name_menu'], PDO::PARAM_STR);
        $statement->bindValue('price_menu', $menu['price_menu'], PDO::PARAM_INT);
        $statement->bindValue('description_menu', $menu['description_menu'], PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Insert new tag(s) in menu in database
     */
    public function insertTagInMenu(array $tagMenu): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLEJOIN . " (`id_tag`, `id_menu`) 
        VALUES (:id_tag, :id_menu)");
        $statement->bindValue('id_tag', $tagMenu['id_tag'], PDO::PARAM_INT);
        $statement->bindValue('id_menu', $tagMenu['id_menu'], PDO::PARAM_INT);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update name menu in database
     */
    public function updateNameMenu(array $menu): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `name_menu` = :name_menu 
        WHERE id_menu=:id_menu");
        $statement->bindValue('id_menu', $menu['id_menu'], PDO::PARAM_INT);
        $statement->bindValue('name_menu', $menu['name_menu'], PDO::PARAM_STR);

        return $statement->execute();
    }

        /**
     * Update price menu in database
     */
    public function updatePriceMenu(array $menu): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `price_menu` = :price_menu 
        WHERE id_menu=:id_menu");
        $statement->bindValue('id_menu', $menu['id_menu'], PDO::PARAM_INT);
        $statement->bindValue('price_menu', $menu['price_menu'], PDO::PARAM_INT);

        return $statement->execute();
    }

            /**
     * Update description menu in database
     */
    public function updateDescriptionMenu(array $menu): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `description_menu` = :description_menu 
        WHERE id_menu=:id_menu");
        $statement->bindValue('id_menu', $menu['id_menu'], PDO::PARAM_INT);
        $statement->bindValue('description_menu', $menu['description_menu'], PDO::PARAM_STR);

        return $statement->execute();
    }

            /**
     * Update note menu in database
     */
    public function updateNoteMenu(array $menu): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `note_menu` = :note_menu 
        WHERE id_menu=:id_menu");
        $statement->bindValue('id_menu', $menu['id_menu'], PDO::PARAM_INT);
        $statement->bindValue('note_menu', $menu['note_menu'], PDO::PARAM_INT);

        return $statement->execute();
    }

        /**
     * Get one row from database by ID.
     */
    public function selectOneMenuById(int $id): array|false
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM " . self::TABLE . " WHERE id_menu=:id_menu");
        $statement->bindValue('id_menu', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    /**
     * Delete row form an ID
     */
    public function deleteMenu(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id_menu=:id_menu");
        $statement->bindValue('id_menu', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}
