<?php

namespace App\Model;

use PDO;

class CookManager extends AbstractManager
{
    public const TABLE = 'cook';

    /**
     * Insert new cook in database
     */
    public function insertCook(array $cook): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`firstname_cook`, `lastname_cook`, 
        `description_cook`, `available_cook`) VALUES (:firstname_cook, :lastname_cook, :description_cook, 
        :available_cook)");
        $statement->bindValue('firstname_cook', $cook['firstname_cook'], PDO::PARAM_STR);
        $statement->bindValue('lastname_cook', $cook['lastname_cook'], PDO::PARAM_STR);
        $statement->bindValue('description_cook', $cook['description_cook'], PDO::PARAM_STR);
        $statement->bindValue('available_cook', $cook['available_cook'], PDO::PARAM_STR);

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
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `available_cook` = :available_cook 
        WHERE id_cook=:id_cook");
        $statement->bindValue('id_cook', $cook['id_cook'], PDO::PARAM_INT);
        $statement->bindValue('available_cook', $cook['available_cook'], PDO::PARAM_STR);

        return $statement->execute();
    }

        /**
     * Get one row from database by ID.
     */
    public function selectOneCookById(int $id): array|false
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM " . self::TABLE . " WHERE id_cook=:id_cook");
        $statement->bindValue('id_cook', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    /**
     * Delete row form an ID
     */
    public function deleteCook(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id_cook=:id_cook");
        $statement->bindValue('id_cook', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}
