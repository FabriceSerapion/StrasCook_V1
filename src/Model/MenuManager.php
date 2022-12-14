<?php

namespace App\Model;

use PDO;

class MenuManager extends AbstractManager
{
    public const TABLE = 'menu';
    public const TABLEJOIN = 'menu_tag';

    public const PATH = 'Menu.html.twig';

    /**
     * Get all menus from database search by tag.
     */
    public function selectAllFromTag(string $tag): array|false
    {
        if (empty($tag)) {
            $statement = $this->pdo->prepare("SELECT * FROM " . static::TABLE);
            $statement->execute();
        } else {
            $statement = $this->pdo->prepare("SELECT menu.id, menu.name_menu, menu.price_menu, menu.note_menu, 
            menu.descr_menu_appetizer, menu.descr_menu_starter, menu.descr_menu_meal, menu.descr_menu_dessert, 
            menu.descr_menu_cheese, menu.descr_menu_cuteness FROM tag
            INNER JOIN menu_tag ON tag.id = menu_tag.id_tag
            INNER JOIN menu ON menu_tag.id_menu = menu.id
            WHERE tag.name_tag = :tag");
            $statement->bindValue('tag', $tag, \PDO::PARAM_STR);
            $statement->execute();
        }
        return $statement->fetchAll();
    }

    /**
     * Get one menu by name of it.
     */
    public function selectOneMenuByName(string $nameMenu): array|false
    {
        $statement = $this->pdo->prepare(
            "SELECT id FROM " . self::TABLE . " WHERE menu.name_menu=:name_menu"
        );
        $statement->bindValue('name_menu', $nameMenu, \PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetch();
    }

    /**
     * Insert new menu in database
     */
    public function insert(array $menu): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
            " (`name_menu`, 
        `price_menu`, 
        `note_menu`, 
        `descr_menu_appetizer`, 
        `descr_menu_starter`, 
        `descr_menu_meal`, 
        `descr_menu_dessert`, 
        `descr_menu_cheese`, 
        `descr_menu_cuteness`
        ) VALUES (
            :name_menu,
            :price_menu,
            0,
            :descr_menu_appetizer,
            :descr_menu_starter,
            :descr_menu_meal,
            :descr_menu_dessert,
            :descr_menu_cheese,
            :descr_menu_cuteness
            )");

        $statement->bindValue('name_menu', $menu['name_menu'], PDO::PARAM_STR);
        $statement->bindValue('price_menu', $menu['price_menu'], PDO::PARAM_INT);
        $statement->bindValue('descr_menu_appetizer', $menu['descr_menu_appetizer'], PDO::PARAM_STR);
        $statement->bindValue('descr_menu_starter', $menu['descr_menu_starter'], PDO::PARAM_STR);
        $statement->bindValue('descr_menu_meal', $menu['descr_menu_meal'], PDO::PARAM_STR);
        $statement->bindValue('descr_menu_dessert', $menu['descr_menu_dessert'], PDO::PARAM_STR);
        $statement->bindValue('descr_menu_cheese', $menu['descr_menu_cheese'], PDO::PARAM_STR);
        $statement->bindValue('descr_menu_cuteness', $menu['descr_menu_cuteness'], PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update menu in database
     */
    public function update(array $newMenu): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET 
        `name_menu` = :name_menu,
        `price_menu` = :price_menu,
        `descr_menu_appetizer` = :descr_menu_appetizer,
        `descr_menu_starter` = :descr_menu_starter,
        `descr_menu_meal` = :descr_menu_meal,
        `descr_menu_dessert` = :descr_menu_dessert,
        `descr_menu_cheese` = :descr_menu_cheese,
        `descr_menu_cuteness` = :descr_menu_cuteness
        WHERE id=:id");
        $statement->bindValue('id', $newMenu['id'], PDO::PARAM_INT);
        $statement->bindValue('name_menu', $newMenu['name_menu'], PDO::PARAM_STR);
        $statement->bindValue('price_menu', $newMenu['price_menu'], PDO::PARAM_INT);
        $statement->bindValue('descr_menu_appetizer', $newMenu['descr_menu_appetizer'], PDO::PARAM_STR);
        $statement->bindValue('descr_menu_starter', $newMenu['descr_menu_starter'], PDO::PARAM_STR);
        $statement->bindValue('descr_menu_meal', $newMenu['descr_menu_meal'], PDO::PARAM_STR);
        $statement->bindValue('descr_menu_dessert', $newMenu['descr_menu_dessert'], PDO::PARAM_STR);
        $statement->bindValue('descr_menu_cheese', $newMenu['descr_menu_cheese'], PDO::PARAM_STR);
        $statement->bindValue('descr_menu_cuteness', $newMenu['descr_menu_cuteness'], PDO::PARAM_STR);

        return $statement->execute();
    }

    public function updateNoteMenu(float $noteMenu, int $id): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET
        `note_menu` = :note_menu WHERE id=:id");
        $statement->bindValue('note_menu', $noteMenu, PDO::PARAM_STR);
        $statement->bindValue('id', $id, PDO::PARAM_INT);

        return $statement->execute();
    }

    /**
     * Overwritting delete method in abstract manager --> deleting lines in table join in more
     */
    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLEJOIN . " WHERE id_menu=:id");
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
        $menuFound = array();
        $menuFound = $this->selectOneMenuByName($item['name_menu']);
        if (!empty($menuFound) && $menuFound['id'] != $item['id']) {
            $errors[] = "Le nom du menu est d??j?? existant !";
        }
        if (empty($item['name_menu'])) {
            $errors[] = "Le nom du menu est n??cessaire !";
        }
        if (empty($item['price_menu'])) {
            $errors[] = "Le prix du menu est n??cessaire !";
        }
        // TODO : check if there is at least 1 description
        return $errors;
    }
}
