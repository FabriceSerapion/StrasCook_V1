<?php

namespace App\Controller;

class BasketController extends AbstractController
{
    /**
     * Display basket page
     */
    public function basket(): string
    {
        return $this->twig->render('Pages/basket.html.twig');
    }
}
