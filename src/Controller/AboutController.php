<?php

namespace App\Controller;

class AboutController extends AbstractController
{
    /**
     * Display about page
     */
    public function about(): string
    {
        return $this->twig->render('Pages/about.html.twig');
    }
}
