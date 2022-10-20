<?php

namespace App\Controller;

class MenusController extends AbstractController
{
    /**
     * Display menus page
     */
    public function allMenus(): string
    {
        return $this->twig->render('Pages/menus.html.twig');
    }
}
