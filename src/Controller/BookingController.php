<?php

namespace App\Controller;

use App\Model\MenuManager;
use App\Model\TagManager;
use App\Model\BookingManager;
use App\Model\CookManager;

class BookingController extends AbstractController
{
    public int $pricePrestation = 10;
    /**
     * Booking informations without menu
     */
    public function booking(string $adress, string $date, string $hour, string $benefit): string
    {
        $menuManager = new MenuManager();
        $tagManager = new TagManager();
        $menus = $menuManager->selectAll();
        foreach ($menus as $idx => $menu) {
            if (is_numeric($menu['id'])) {
                $tagsFromMenu = $tagManager->selectAllTagsFromMenu($menu['id']);
                $menus[$idx]["tags"] = $tagsFromMenu;
            }
        }
        $data = ['menus' => $menus];

        //VALIDATION BY TWIG --> INFORMATIONS ASKED IN CERTAIN FORMS

        //Verify if user is connected
        if (!empty($_SESSION) && $_SESSION['authed']) {
            $data ['adress'] = $adress;
            $data ['date'] = $date;
            $data ['hour'] = $hour;
            $data ['benefit'] = $benefit;
            $data['username'] = $_SESSION["username"];
        } else {
            header('Location:/');
            return '';
        }

        return $this->twig->render('Pages/menus.html.twig', $data);
    }

    /**
     * Booking menu with informations
     */
    public function bookingMenu(string $adress, string $date, string $hour, string $benefit, int $idMenu): string
    {
        $data = array();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $menuManager = new MenuManager();
            if (is_numeric($idMenu)) {
                $menu = $menuManager->selectOneById($idMenu);
            }
            //VALIDATION BY TWIG --> INFORMATIONS ASKED IN CERTAIN FORMS
            $data = ['menu' => $menu];
            $data ['adress'] = $adress;
            $data ['date'] = $date;
            $data ['hour'] = $hour;
            $data ['benefit'] = $benefit;
            $data ['pricePrestation'] = $this->pricePrestation;
        }
        return $this->twig->render('Pages/summary.html.twig', $data);
    }

    /**
     * Booking menu with informations
     */
    public function bookingValidation(string $adress, string $date, string $hour, string $benefit, int $idMenu): string
    {
        $bookingManager = new BookingManager();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $booking = array_map('trim', $_POST);
            foreach ($booking as $key => $element) {
                $element = trim(htmlspecialchars($booking[$key]));
                $booking[$key] = $element;
            }

            $booking['nbMenu'] = intval($booking['nbMenu']);

            $menuManager = new MenuManager();
            if (is_numeric($idMenu)) {
                $menu = $menuManager->selectOneById($idMenu);
            }
            $cookManager = new CookManager();
            $intHour = intval($hour);
            if (is_numeric($intHour)) {
                $idCook = $cookManager->selectAnId(hour: $intHour);
            }

            if (!$idCook) {
                $data['error'] = "Une erreur est survenue, il n'y'a pas de cuisinier disponible";
                return $this->twig->render('Pages/summary.html.twig', $data);
            }

            //INFORMATIONS FOR BOOKING --> DATE + ADRESS + PRIX + IDCOOK
            $booking['adress_booking'] = $adress;
            $booking['date_booking'] = $date;
            $booking['price_prestation'] = $this->pricePrestation + (floatval($menu['price_menu']) *
             floatval($booking['nbMenu']));
            $booking['id_cook'] = $idCook;

            //INFORMATIONS FOR BOOK_MENU
            $booking['id_menu'] = $idMenu;
            if ($benefit === 'dinner') {
                $booking['is_lesson'] = false;
            }

            //Validation for the item
            $errors = $bookingManager->validation($booking);

             // if validation is ok, insert and redirection
            if (empty($errors)) {
                $bookingManager->insertBooking($booking, $_SESSION['user_id']);
                //SEARCH IDBOOKING FOR LINKING BOOKING TO MENU BOOKED
                $idBooking = $bookingManager->selectLastId($_SESSION['user_id']);
                $booking['id_booking'] = $idBooking['id'];
                if (is_numeric($booking['id_booking'])) {
                    $bookingManager->insertMenuInBooking($booking);
                }
                header('Location:/');
                return '';
            } else {
                //Show errors
                $data = [];
                $data['adress'] = $adress;
                $data['date'] = $date;
                $data['hour'] = $hour;
                $data['benefit'] = $benefit;
                $data['pricePrestation'] = $this->pricePrestation;
                $data['menu'] = $menu;
                $data['errors'] = $errors;
                return $this->twig->render('Pages/summary.html.twig', $data);
            }
        }
        return $this->twig->render('Pages/summary.html.twig');
    }
}
