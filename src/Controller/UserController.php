<?php

namespace App\Controller;

class UserController extends AbstractController
{
    /**
     * Display user page
     */
    public function userConnect(): string
    {
        return $this->twig->render('Pages/user.html.twig');
    }
}
