<?php

namespace App\Model;

use PDO;

class BookingManager extends AbstractManager
{
    public const TABLE = 'BOOKING';
    public const TABLEJOIN = 'booking_menu';

    public const PATH = 'Booking.html.twig';

    /**
     * Get all row from database.
     */
    public function selectAll(int $limit = 0, string $orderBy = '', string $direction = 'ASC', int $idUser = 0): array
    {
        $query = 'SELECT booking.date_booking, booking.adress_booking, booking.price_prestation, menu.name_menu, 
        booking_menu.quantity_prestation, cook.firstname_cook FROM booking 
        INNER JOIN cook ON booking.id_cook = cook.id
        LEFT JOIN booking_menu ON booking.id = booking_menu.id_booking
        LEFT JOIN menu ON booking_menu.id_menu = menu.id';
        if ($idUser > 0) {
            $query .= ' WHERE booking.id_user = ' . $idUser;
        }
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }
        if ($limit > 0) {
            $query .= ' LIMIT ' . $limit;
        }

        return $this->pdo->query($query)->fetchAll();
    }

    /**
     * Get all row from database.
     */
    public function selectLastId(int $idUser): array|false
    {
        $query = 'SELECT id from booking  where id_user = ' . $idUser . ' order by id DESC LIMIT 1;';

        return $this->pdo->query($query)->fetch();
    }

    /**
     * Insert new booking in database
     */
    public function insertBooking(array $booking, int $idUser): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`date_booking`, `adress_booking`, 
        `price_prestation`, `id_cook`, `id_user`) VALUES (:date_booking, :adress_booking, 
        :price_prestation, :id_cook, :id_user)");
        $statement->bindValue('date_booking', $booking['date_booking'], PDO::PARAM_STR);
        $statement->bindValue('adress_booking', $booking['adress_booking'], PDO::PARAM_STR);
        $statement->bindValue('price_prestation', $booking['price_prestation'], PDO::PARAM_INT);
        $statement->bindValue('id_cook', $booking['id_cook'], PDO::PARAM_INT);
        $statement->bindValue('id_user', $idUser, PDO::PARAM_INT);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Insert new tag(s) in menu in database
     */
    public function insertMenuInBooking(array $menuBooking): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLEJOIN . " (`id_booking`, `id_menu`, 
        `quantity_prestation`, `is_lesson`) VALUES (:id_booking, :id_menu, :quantity_prestation, :is_lesson)
        ");
        $statement->bindValue('id_booking', $menuBooking['id_booking'], PDO::PARAM_INT);
        $statement->bindValue('id_menu', $menuBooking['id_menu'], PDO::PARAM_INT);
        $statement->bindValue('quantity_prestation', $menuBooking['nbMenu'], PDO::PARAM_INT);
        $statement->bindValue('is_lesson', $menuBooking['is_lesson'], PDO::PARAM_BOOL);

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
     * Validation method specific for this item
     */
    public function validation(array $booking): array
    {
        $errors = array();
        if (empty($booking['price_prestation'])) {
            $errors[] = "Vous devez donner une quantité !";
        }
        if (empty($booking['id_menu'] || !is_numeric($booking['id_menu']))) {
            $errors[] = "Vous devez choisir un menu !";
        }
        if (empty($booking['id_cook'] || !is_numeric($booking['id_cook']))) {
            $errors[] = "Il n'y a pas de cuisinier disponible !";
        }
        return $errors;
    }
}
