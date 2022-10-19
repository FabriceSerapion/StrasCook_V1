<?php

namespace App\Controller;

class GiftsController extends AbstractController
{
    /**
     * Display gifts page
     */
    public function gifts(): string
    {
        return $this->twig->render('Pages/gifts.html.twig');
    }
}
