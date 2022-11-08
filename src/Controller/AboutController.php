<?php

namespace App\Controller;

class AboutController extends AbstractController
{
    /**
     * Display about page
     */
    public function about(): string
    {
        //Verify if user is connected
        $data = array();
        if (!empty($_SESSION) && $_SESSION['authed']) {
            $username = $_SESSION["username"];
            $data['username'] = $username;
        }
        return $this->twig->render('Pages/about.html.twig', $data);
    }
}
