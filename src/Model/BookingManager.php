<?php

namespace App\Model;

use PDO;

class BookingManager extends AbstractManager
{
    public const TABLE = 'BOOKING';
    public const TABLEJOIN = 'booking_menu';

    /**
     * Insert new booking in database
     */
    public function insertBooking(array $booking): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`date_booking`, `adress_booking`, 
        `price_prestation`, `is_lesson`, `id_cook`) VALUES (:date_booking, :adress_booking, :price_prestation, 
        :is_lesson, :id_cook)");
        $statement->bindValue('date_booking', $booking['date_booking'], PDO::PARAM_STR);
        $statement->bindValue('adress_booking', $booking['adress_booking'], PDO::PARAM_STR);
        $statement->bindValue('price_prestation', $booking['price_prestation'], PDO::PARAM_INT);
        $statement->bindValue('is_lesson', $booking['is_lesson'], PDO::PARAM_BOOL);
        $statement->bindValue('id_cook', $booking['id_cook'], PDO::PARAM_INT);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Insert new tag(s) in menu in database
     */
    public function insertMenuInBooking(array $menuBooking): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLEJOIN . " (`id_booking`, `id_menu`) 
        VALUES (:id_booking, :id_menu)");
        $statement->bindValue('id_booking', $menuBooking['id_booking'], PDO::PARAM_INT);
        $statement->bindValue('id_menu', $menuBooking['id_menu'], PDO::PARAM_INT);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update date booking in database
     */
    public function updateDateBooking(array $booking): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `date_booking` = :date_booking 
        WHERE id_booking=:id_booking");
        $statement->bindValue('id_booking', $booking['id_booking'], PDO::PARAM_INT);
        $statement->bindValue('date_booking', $booking['date_booking'], PDO::PARAM_STR);

        return $statement->execute();
    }

        /**
     * Update adress booking in database
     */
    public function updateAdressBooking(array $booking): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `adress_booking` = :adress_booking 
        WHERE id_booking=:id_booking");
        $statement->bindValue('id_booking', $booking['id_booking'], PDO::PARAM_INT);
        $statement->bindValue('adress_booking', $booking['adress_booking'], PDO::PARAM_STR);

        return $statement->execute();
    }

    /**
     * Update price booking in database
     */
    public function updatePriceBooking(array $booking): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `price_prestation` = :price_prestation 
        WHERE id_booking=:id_booking");
        $statement->bindValue('id_booking', $booking['id_booking'], PDO::PARAM_INT);
        $statement->bindValue('price_prestation', $booking['price_prestation'], PDO::PARAM_INT);

        return $statement->execute();
    }

    /**
     * Update id cook in database
     */
    public function updateCookBooking(array $booking): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `id_cook` = :id_cook 
        WHERE id_booking=:id_booking");
        $statement->bindValue('id_booking', $booking['id_booking'], PDO::PARAM_INT);
        $statement->bindValue('id_cook', $booking['id_cook'], PDO::PARAM_INT);

        return $statement->execute();
    }

        /**
     * Get one row from database by ID.
     */
    public function selectOneBookingById(int $id): array|false
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM " . self::TABLE . " WHERE id_booking=:id_booking");
        $statement->bindValue('id_booking', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    /**
     * Delete row form an ID
     */
    public function deleteCook(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id_booking=:id_booking");
        $statement->bindValue('id_booking', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}
