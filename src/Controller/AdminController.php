<?php

namespace App\Controller;

class AdminController extends AbstractController
{
    /**
     * Display interface administrator page
     */
    public function admin(): string
    {
        return $this->twig->render('Admin/admin.html.twig');
    }
}
