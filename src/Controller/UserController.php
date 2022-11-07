<?php

namespace App\Controller;

use App\Model\UserManager;

final class UserController extends AbstractController
{
    //TODO finish the signup logique
    public function signup(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // TODO validation (code injection, empty values, string patterns, etc.)
            if (!empty($_POST['username']) && !empty($_POST['password'])) {
                $user = new UserManager();
                $user->insert([
                    'username' => $_POST['username'],
                    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT)
                ]);
                $user = $user->selectOneByUsername($_POST['username']);

                $_SESSION['authed'] = true;
                $_SESSION['username'] = $_POST['username'];
                $data = ['user' => $user];
                return $this->twig->render('Home/index.html.twig', $data);
            } else {
                session_destroy();
                $data = ['error' => "Une erreur est survenue"];
                return $this->twig->render('Auth/signup.html.twig', $data);
            }
        }
        return $this->twig->render('Auth/signup.html.twig');
    }

    public function login(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // TODO validation (code injection, empty values, string patterns, etc.)
            $user = new UserManager();
            $user = $user->selectOneByUsername($_POST['username']);
            if (!empty($user)) {
                //TODO modify the password verification to be stronger
                if (password_verify($_POST['password'], $user["password"])) {
                    $_SESSION['authed'] = true;
                    $_SESSION['username'] = $user["username"];
                    $data = ['user' => $user];
                    if ($user["isAdmin"]) {
                        header('Location:/admin');
                        return '';
                    } else {
                        return $this->twig->render('Home/index.html.twig', $data);
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

    public function logout(): void
    {
        session_destroy();
        \header('Location: /login');
    }
}
