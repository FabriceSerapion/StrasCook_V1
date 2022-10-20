<?php

namespace App\Controller;

class HomeController extends AbstractController
{
    /**
     * Display home page
     */
    public function index(): string
    {
        $errors = array();
        if ($_POST) {//strart validation
            if (empty($_POST['adress'])) {
                $errors['adress1'] = "Votre adresse est requise";
            }

            if (empty($_POST['date'])) {
                $errors['date1'] = "Votre date de réservation est requise";
            }

            if (empty($_POST['hour'])) {
                $errors['hour1'] = "Votre heure de réservation souhétée est obligatoire";
            }

            if (empty($_POST['benefit'])) {
                $errors['benefit1'] = "Veuillez choisir un service";
            }
        }
        return $this->twig->render('Home/index.html.twig', [
            'errors' => $errors
        ]);
    }
}
