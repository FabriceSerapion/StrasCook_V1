<?php

namespace App\Model;

use PDO;

class CookManager extends AbstractManager
{
    public const TABLE = 'cook';

    /**
     * Insert new cook in database
     */
    public function insert(array $cook): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`firstname_cook`, `lastname_cook`, 
        `description_cook`, `available_cook`) VALUES (:firstname_cook, :lastname_cook, :description_cook, 
        :begin_cook, :end_cook)");
        $statement->bindValue('firstname_cook', $cook['firstname_cook'], PDO::PARAM_STR);
        $statement->bindValue('lastname_cook', $cook['lastname_cook'], PDO::PARAM_STR);
        $statement->bindValue('description_cook', $cook['description_cook'], PDO::PARAM_STR);
        $statement->bindValue('begin_cook', $cook['begin_cook'], PDO::PARAM_STR);
        $statement->bindValue('end_cook', $cook['end_cook'], PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update firstname cook in database
     */
    public function updateFirstNameCook(array $cook): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `firstname_cook` = :firstname_cook 
        WHERE id_cook=:id_cook");
        $statement->bindValue('id_cook', $cook['id_cook'], PDO::PARAM_INT);
        $statement->bindValue('firstname_cook', $cook['firstname_cook'], PDO::PARAM_STR);

        return $statement->execute();
    }

        /**
     * Update lastname cook in database
     */
    public function updateLastNameCook(array $cook): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `lastname_cook` = :lastname_cook 
        WHERE id_cook=:id_cook");
        $statement->bindValue('id_cook', $cook['id_cook'], PDO::PARAM_INT);
        $statement->bindValue('lastname_cook', $cook['lastname_cook'], PDO::PARAM_STR);

        return $statement->execute();
    }

            /**
     * Update description cook in database
     */
    public function updateDescriptionCook(array $cook): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `description_cook` = :description_cook 
        WHERE id_cook=:id_cook");
        $statement->bindValue('id_cook', $cook['id_cook'], PDO::PARAM_INT);
        $statement->bindValue('description_cook', $cook['description_cook'], PDO::PARAM_STR);

        return $statement->execute();
    }

            /**
     * Update available cook in database
     */
    public function updateAvailableCook(array $cook): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `begin_cook` = :begin_cook,
        `end_cook` = :end_cook WHERE id_cook=:id_cook");
        $statement->bindValue('id_cook', $cook['id_cook'], PDO::PARAM_INT);
        $statement->bindValue('begin_cook', $cook['begin_cook'], PDO::PARAM_STR);
        $statement->bindValue('end_cook', $cook['end_cook'], PDO::PARAM_STR);

        return $statement->execute();
    }

    /**
     * Validation method specific for this item
     */
    public function validation(array $item): array
    {
        $errors = array();
        if (empty($item['firstname_cook'])) {
            $errors[] = "Le pénonom du cuisinier est nécessaire !";
        }
        if (empty($item['lastname_cook'])) {
            $errors[] = "Le nom du cuisinier est nécessaire !";
        }
        if (empty($item['begin_cook']) || empty($item['end_cook'])) {
            $errors[] = "Veuillez préciser les disponibilités !";
        }
        return $errors;
    }
}
