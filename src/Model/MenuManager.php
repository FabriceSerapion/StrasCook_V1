<?php

namespace App\Model;

use PDO;

class MenuManager extends AbstractManager
{
    public const TABLE = 'menu';

    // TODO insertion and update methods


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
    public function update(array $menu): bool
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
        $statement->bindValue('id', $menu['id'], PDO::PARAM_INT);
        $statement->bindValue('name_menu', $menu['name_menu'], PDO::PARAM_STR);
        $statement->bindValue('price_menu', $menu['price_menu'], PDO::PARAM_INT);
        $statement->bindValue('descr_menu_appetizer', $menu['descr_menu_appetizer'], PDO::PARAM_STR);
        $statement->bindValue('descr_menu_starter', $menu['descr_menu_starter'], PDO::PARAM_STR);
        $statement->bindValue('descr_menu_meal', $menu['descr_menu_meal'], PDO::PARAM_STR);
        $statement->bindValue('descr_menu_dessert', $menu['descr_menu_dessert'], PDO::PARAM_STR);
        $statement->bindValue('descr_menu_cheese', $menu['descr_menu_cheese'], PDO::PARAM_STR);
        $statement->bindValue('descr_menu_cuteness', $menu['descr_menu_cuteness'], PDO::PARAM_STR);

        return $statement->execute();
    }

/**
     * Validation method specific for this item
     */
    public function validation(array $item): array
    {
        $errors = array();
        if (empty($item['name_menu'])) {
            $errors[] = "Le nom du menu est nécessaire !";
        }
        if (empty($item['price_menu'])) {
            $errors[] = "Le prix du menu est nécessaire !";
        }
        // TODO : check if there is at least 1 description
        return $errors;
    }
}
