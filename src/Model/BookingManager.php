<?php

namespace App\Model;

use PDO;

class BookingManager extends AbstractManager
{
    public const TABLE = 'BOOKING';
    public const TABLEJOIN = 'booking_menu';

    /**
     * Get all row from database.
     */
    public function selectAll(int $limit = 0, string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT booking.date_booking, booking.adress_booking, booking.price_prestation, menu.name_menu, 
        booking_menu.quantity_prestation, booking_menu.is_lesson, cook.firstname_cook FROM booking 
        INNER JOIN cook ON booking.id_cook = cook.id
        INNER JOIN booking_menu ON booking.id = booking_menu.id_booking
        INNER JOIN menu ON booking_menu.id_menu = menu.id';
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }
        if ($limit > 0) {
            $query .= ' LIMIT ' . $limit;
        }

        return $this->pdo->query($query)->fetchAll();
    }

    /**
     * Insert new booking in database
     */
    public function insertBooking(array $booking): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`date_booking`, `adress_booking`, 
        `price_prestation`, `id_cook`) VALUES (:date_booking, :adress_booking, 
        :price_prestation, :id_cook)");
        $statement->bindValue('date_booking', $booking['date_booking'], PDO::PARAM_STR);
        $statement->bindValue('adress_booking', $booking['adress_booking'], PDO::PARAM_STR);
        $statement->bindValue('price_prestation', $booking['price_prestation'], PDO::PARAM_INT);
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
}
