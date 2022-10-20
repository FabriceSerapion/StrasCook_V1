<?php

namespace App\Controller;

class LessonsController extends AbstractController
{
    /**
     * Display lessons page
     */
    public function lessons(): string
    {
        return $this->twig->render('Pages/lessons.html.twig');
    }
}
