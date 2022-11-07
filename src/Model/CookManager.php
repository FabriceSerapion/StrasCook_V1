<?php

namespace App\Model;

use PDO;

class CookManager extends AbstractManager
{
    public const TABLE = 'cook';

    public const PATH = 'Cook.html.twig';

    /**
     * Get all row from database.
     */
    public function selectAnId(int $hour, string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'select id from cook where ' . $hour . ' > begin_cook and ' . $hour . ' < end_cook LIMIT 1';
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }

        return $this->pdo->query($query)->fetch();
    }

    /**
     * Insert new cook in database
     */
    public function insert(array $cook): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`firstname_cook`, `lastname_cook`, 
        `description_cook`, `begin_cook`, `end_cook`) VALUES (:firstname_cook, :lastname_cook, :description_cook, 
        :begin_cook, :end_cook)");
        $statement->bindValue('firstname_cook', $cook['firstname_cook'], PDO::PARAM_STR);
        $statement->bindValue('lastname_cook', $cook['lastname_cook'], PDO::PARAM_STR);
        $statement->bindValue('description_cook', $cook['description_cook'], PDO::PARAM_STR);
        $statement->bindValue('begin_cook', $cook['begin_cook'], PDO::PARAM_INT);
        $statement->bindValue('end_cook', $cook['end_cook'], PDO::PARAM_INT);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update cook in database
     */
    public function update(array $cook): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET 
        `firstname_cook` = :firstname_cook,
        `lastname_cook` = :lastname_cook,
        `description_cook` = :description_cook,
        `begin_cook` = :begin_cook,
        `end_cook` = :end_cook
        WHERE id=:id");
        $statement->bindValue('id', $cook['id'], PDO::PARAM_INT);
        $statement->bindValue('firstname_cook', $cook['firstname_cook'], PDO::PARAM_STR);
        $statement->bindValue('lastname_cook', $cook['lastname_cook'], PDO::PARAM_STR);
        $statement->bindValue('description_cook', $cook['description_cook'], PDO::PARAM_STR);
        $statement->bindValue('begin_cook', $cook['begin_cook'], PDO::PARAM_INT);
        $statement->bindValue('end_cook', $cook['end_cook'], PDO::PARAM_INT);

        return $statement->execute();
    }

    /**
     * Validation method specific for this item
     */
    public function validation(array $item): array
    {
        $errors = array();
        if (empty($item['lastname_cook'])) {
            $errors[] = "Le nom du cuisinier est nécessaire !";
        }
        if (empty($item['firstname_cook'])) {
            $errors[] = "Le prénom du cuisinier est nécessaire !";
        }
        if (empty($item['begin_cook']) || empty($item['end_cook'])) {
            $errors[] = "Veuillez préciser les disponibilités !";
        }
        return $errors;
    }
}
