<?php

namespace App\Controller;

use App\Model\UserManager;
use App\Model\BookingManager;
use App\Model\MenuManager;
use App\Model\NoteManager;

final class UserController extends AbstractController
{
    /**
     * Redirection depending on user right
     */
    public function indexUser(): ?string
    {
        if (!empty($_SESSION) && $_SESSION['authed'] && $_SESSION['isAdmin']) {
            header('Location:/admin');
            return null;
        } elseif (!empty($_SESSION) && $_SESSION['authed'] && !$_SESSION['isAdmin']) {
            header('Location:/userconnected');
            return null;
        } else {
            $this->login();
            return $this->twig->render('Auth/login.html.twig');
        }
    }

    /**
     * Display user page
     */
    public function userConnected(): string
    {
        //GET ALL BOOKS
        $bookManager = new BookingManager();
        $bookings = $bookManager->selectAll(orderBy: 'booking.date_booking', idUser: $_SESSION['user_id']);

        //GET ALL NOTES
        $noteManager = new NoteManager();
        $allBooked = $noteManager->selectAllMenuBooked(idUser: $_SESSION['user_id']);

        foreach ($allBooked as $idx => $booked) {
            $noted = $noteManager->selectNoteFrmMenuAndUser($booked['id'], $_SESSION['user_id']);
            if (!empty($noted)) {
                $allBooked[$idx]['user_note'] = $noted['user_note'];
            } else {
                $allBooked[$idx]['user_note'] = '';
            }
        }

        //PUSH DATAS IN TWIG
        $data = ['bookings' => $bookings];
        $data ['notes'] = $allBooked;

        return $this->twig->render('Pages/user.html.twig', $data);
    }

    /**
     * Add a new note
     */
    public function add(int $idMenu): ?string
    {
        $noteManager = new NoteManager();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $note = array_map('trim', $_POST);

            // if validation is ok, insert and redirection
            //TODO VALIDATION DONE FOR 08/11 IN THE MORNING
            // if (empty($errors)) {
                $noteManager->modifyUserNote($note['user_note'], $idMenu, $_SESSION['user_id'], 1);
                $this->modifyNoteGlobal($note['user_note'], $idMenu, 0);
                header('Location:/userConnected');
                return null;
            // } else {
            //     return $this->twig->render('Item/add' . $noteManager::PATH);
            // }
        }
        return $this->twig->render('Item/add' . $noteManager::PATH, ['notAdmin' => true]);
    }

    /**
     * Edit a note
     */
    public function edit(int $idMenu): ?string
    {
        $noteManager = new NoteManager();

        //Finding the correct item
        $noted = $noteManager->selectNoteFrmMenuAndUser($idMenu, $_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $note = array_map('trim', $_POST);

            // if validation is ok, insert and redirection
            //TODO VALIDATION DONE FOR 08/11 IN THE MORNING
            // if (empty($errors)) {
                $this->modifyNoteGlobal($note['user_note'], $idMenu, $noted['user_note']);
                $noteManager->modifyUserNote($note['user_note'], $idMenu, $_SESSION['user_id'], 0);
                header('Location:/userConnected');
                return null;
            // } else {
            //     return $this->twig->render('Item/edit' . $noteManager::PATH);
            // }
        }
        $data = array();
        $data['notAdmin'] = true;
        $data['menu_note'] = $noted;
        return $this->twig->render('Item/edit' . $noteManager::PATH, $data);
    }

    /**
     * Modifying note when user modify his note for a menu
     */
    public function modifyNoteGlobal(string $userNote, int $idMenu, int $oldNote): void
    {
        $noteManager = new NoteManager();
        $menuManager = new MenuManager();
        //TODO VALIDATION DONE FOR 08/11 IN THE MORNING
        $noteMenu = $noteManager->selectAllNotesFrmMenu($idMenu);
        $number = $noteMenu['noteCount'];

        if ($oldNote == 0) {
            $finalNote = $noteMenu['noteUser'] / $number;
        } else {
            $finalNote = ($noteMenu['noteUser'] - $oldNote + floatval($userNote)) / $number;
        }
        //TODO VALIDATION DONE FOR 08/11 IN THE MORNING
        $menuManager->updateNoteMenu($finalNote, $idMenu);
    }

    //TODO finish the signup logique
    public function signup(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // TODO validation (code injection, empty values, string patterns, etc.)
            // TODO feedback to the user is something wrong
            // TODO create user
            $user = new UserManager();
            $user->insert([
                'username' => $_POST['username'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT)
            ]);

            // TODO redirect
            \header('Location: /');
        }
        return $this->twig->render('Auth/signup.html.twig');
    }

    public function login(): string
    {
        // TODO get sent username and password from form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // TODO validation (code injection, empty values, string patterns, etc.)
            $user = new UserManager();
            $user = $user->selectOneByUsername($_POST['username']);
            if (!empty($user)) {
                //TODO modify the password verification to be stronger
                if ($_POST['password'] === $user["password"]) {
                    $_SESSION['authed'] = true;
                    $_SESSION['username'] = $user["username"];
                    $_SESSION['isAdmin'] = $user["isAdmin"];
                    $_SESSION['user_id'] = $user["id"];
                    $data = ['user' => $user];
                    if ($user["isAdmin"]) {
                        header('Location:/admin');
                        return '';
                    } else {
                        header('Location:/userconnected');
                        return '';
                    }
                } else {
                    session_destroy();
                    $data = ['error' => "Votre mot de passe est erroné"];
                    return $this->twig->render('Auth/login.html.twig', $data);
                }
            } else {
                $data = ['error' => "Vous n'êtes pas reconnu"];
                return $this->twig->render('Auth/login.html.twig', $data);
            }
        }
        return $this->twig->render('Auth/login.html.twig');
    }
    //TODO finish to implement logout logique
    public function logout(): void
    {
        session_destroy();
        \header('Location: /');
    }
}
