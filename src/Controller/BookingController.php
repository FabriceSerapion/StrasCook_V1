<?php

namespace App\Controller;

use App\Model\MenuManager;
use App\Model\TagManager;
use App\Model\CookManager;
use App\Model\BookingManager;

class BookingController extends AbstractController
{
    /**
     * Booking informations without menu
     */
    public function booking(string $adress, string $date, string $hour, string $benefit): string
    {
        // TODO VALIDATION
        $menuManager = new MenuManager();
        $tagManager = new TagManager();
        $menus = $menuManager->selectAll();
        foreach ($menus as $idx => $menu) {
            $tagsFromMenu = $tagManager->selectAllTagsFromMenu($menu['id']);
            $menus[$idx]["tags"] = $tagsFromMenu;
        }
        $data = ['menus' => $menus];

        $data ['adress'] = $adress;
        $data ['date'] = $date;
        $data ['hour'] = $hour;
        $data ['benefit'] = $benefit;

        return $this->twig->render('Pages/menus.html.twig', $data);
    }

    /**
     * Booking menu with informations
     */
    public function bookingMenu(string $adress, string $date, string $hour, string $benefit, int $idMenu): string
    {
        $data = array();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // TODO VALIDATION
            $menuManager = new MenuManager();
            $menu = $menuManager->selectOneById($idMenu);
            $data = ['menu' => $menu];
            $data ['adress'] = $adress;
            $data ['date'] = $date;
            $data ['hour'] = $hour;
            $data ['benefit'] = $benefit;
        }
        return $this->twig->render('Pages/summary.html.twig', $data);
    }

    /**
     * Booking menu with informations
     */
    // public function bookingValidation(string $adress, string $date, string $hour, string $benefit,
    // int $idMenu): string
    public function bookingValidation(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // TODO VALIDATION + ADD BOOKING IN SQL TABLE
        }
        return $this->twig->render('');
    }
}
