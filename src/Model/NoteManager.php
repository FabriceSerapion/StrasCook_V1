<?php

namespace App\Model;

use PDO;

class NoteManager extends AbstractManager
{
    public const PATH = 'Menu.html.twig';

    /**
     * Get all notes for one user from database by ID.
     */
    public function selectAllMenuBooked(int $idUser): array|false
    {
        $statement = $this->pdo->prepare(
            "select DISTINCT menu.note_menu, menu.name_menu, menu.id 
            from booking inner join booking_menu on booking.id = booking_menu.id_booking
            inner join menu on booking_menu.id_menu = menu.id where id_user =:id_user"
        );
        $statement->bindValue('id_user', $idUser, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }

    /**
     * Get all notes for one menu from database by ID.
     */
    public function selectNoteFrmMenuAndUser(int $idMenu, int $idUser): array|false
    {
        $statement = $this->pdo->prepare("select user_note from menu_note 
        where id_menu = :id_menu and id_user = :id_user");
        $statement->bindValue('id_menu', $idMenu, \PDO::PARAM_INT);
        $statement->bindValue('id_user', $idUser, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();
    }

    /**
     * Get all notes for one menu from database by ID.
     */
    public function selectAllNotesFrmMenu(int $idMenu): array|false
    {
        $statement = $this->pdo->prepare("SELECT count(user_note) as noteCount, sum(user_note) as noteUser, 
        menu.note_menu from menu_note INNER JOIN menu ON menu_note.id_menu = menu.id 
        WHERE id_menu = :id_menu");
        $statement->bindValue('id_menu', $idMenu, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();
    }

    /**
     * Get note from one menu and one user from database by ID.
     */
    public function selectOneNote(int $idUser, int $idMenu): array|false
    {
        $statement = $this->pdo->prepare(
            "SELECT menu.note_menu, menu.name_menu, menu_note.user_note, menu.id FROM user
        INNER JOIN booking ON user.id = booking.id_user
        INNER join booking_menu on booking.id = booking_menu.id_booking
        INNER join menu on booking_menu.id_menu = menu.id
        LEFT join menu_note on booking_menu.id_menu = menu_note.id_menu
        WHERE user.id=:id_user AND menu_note.id_menu = :id_menu"
        );
        $statement->bindValue('id_user', $idUser, \PDO::PARAM_INT);
        $statement->bindValue('id_menu', $idMenu, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();
    }

     /**
     * Modify one note for a user and a menu
     */
    public function modifyUserNote(string $userNote, int $idMenu, int $idUser, int $type): bool
    {
        $statement2 = null;
        if ($type == 0) {
            $statement2 = $this->pdo->prepare("UPDATE menu_note SET 
            `user_note` = :user_note WHERE id_menu = :id_menu AND id_user = :id_user");
        } elseif ($type == 1) {
            $statement2 = $this->pdo->prepare("INSERT INTO menu_note (`id_menu`, `id_user`, `user_note`) 
            VALUES (:id_menu, :id_user, :user_note)");
        }
        $statement2->bindValue('id_menu', $idMenu, PDO::PARAM_INT);
        $statement2->bindValue('id_user', $idUser, PDO::PARAM_INT);
        $statement2->bindValue('user_note', $userNote, PDO::PARAM_STR);
        return $statement2->execute();
    }

        /**
     * Validation method specific for this item
     */
    public function validation(array $note): array
    {
        $errors = array();
        // var_dump($note);die();
        if (
            empty($note['user_note'] || !is_numeric($note['user_note'])) ||
            ($note['user_note'] > 5 || $note['user_note'] < 0)
        ) {
            $errors[] = "Vous devez mettre une note valide entre 0 et 5 compris !";
        }
        return $errors;
    }
}
