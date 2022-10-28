<?php

namespace App\Controller;

use App\Model\AbstractManager;
use App\Model\MenuManager;
use App\Model\TagManager;
use App\Model\CookManager;
use App\Model\BookingManager;

class AdminController extends AbstractController
{
    /**
     * Display interface administrator page
     */
    public function indexAdmin(): string
    {
        //GET ALL MENUS
        $menuManager = new MenuManager();
        $menus = $menuManager->selectAll(orderBy: 'note_menu');

        //GET ALL TAGS
        $tagManager = new TagManager();
        $tags = $tagManager->selectAll(orderBy: 'name_tag');

        //LINK TAGS WITH MENUS
        foreach ($menus as $idx => $menu) {
            $tagsFromMenu = $tagManager->selectAllTagsFromMenu($menu['id']);
            $menus[$idx]["tags"] = $tagsFromMenu;
        }

        //GET ALL COOKS
        $cookManager = new CookManager();
        $cooks = $cookManager->selectAll(orderBy: 'cook.firstname_cook');

        //GET ALL BOOKS
        $bookManager = new BookingManager();
        $bookings = $bookManager->selectAll(limit: 5, orderBy: 'booking.date_booking');

        //PUSH DATAS IN TWIG
        $data = ['menus' => $menus];
        $data ['tags'] = $tags;
        $data ['cooks'] = $cooks;
        $data ['bookings'] = $bookings;

        return $this->twig->render('Admin/admin.html.twig', $data);
    }

    /**
     * Method for choosing the correct table for CRUD
     */
    public function chooseTable(string $table)
    {
        switch ($table) {
            case '0':
                $manager = new MenuManager();
                break;
            case '1':
                $manager = new TagManager();
                break;
            case '2':
                $manager = new CookManager();
                break;
            case '3':
                $manager = new BookingManager();
                break;
            default:
                $manager = 'failed';
                break;
        }
        return $manager;
    }

    /**
     * Method for choosing the correct path for CRUD
     */
    public function chooseReturn(string $table)
    {
        switch ($table) {
            case '0':
                $path = 'Menu.html.twig';
                break;
            case '1':
                $path = 'Tag.html.twig';
                break;
            case '2':
                $path = 'Cook.html.twig';
                break;
            case '3':
                $path = 'Booking.html.twig';
                break;
            default:
                $path = 'failed';
                break;
        }
        return $path;
    }

    /**
     * Add a new item : item will be choosed in this method between Tag / Menu / Cook / booking
     */
    public function add(string $table): ?string
    {
        //Looking for the correct path (twig)
        $path = $this->chooseReturn($table);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Looking for the correct table
            $manager = $this->chooseTable($table);

            // clean $_POST data
            $item = array_map('trim', $_POST);
            foreach ($item as $key => $element) {
                $element = trim(htmlspecialchars($item[$key]));
                $item[$key] = $element;
            }

            $errors = $manager->validation($item);

            // if validation is ok, insert and redirection
            if (empty($errors)) {
                $manager->insert($item);
                header('Location:/admin');
                return null;
            } else {
                return $this->twig->render('Item/add' . $path, ['errors' => $errors]);
            }
        }
        return $this->twig->render('Item/add' . $path);
    }

    /**
     * Edit a specific item : item will be choosed in this method between Tag / Menu / Cook / booking
     */
    public function edit(int $id, string $table): ?string
    {
        //Looking for the correct table
        $manager = $this->chooseTable($table);
        //Looking for the correct path (twig)
        $path = $this->chooseReturn($table);

        $item = $manager->selectOneById($id);
        $itemSave = $item;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $item = array_map('trim', $_POST);
            foreach ($item as $key => $element) {
                $element = trim(htmlspecialchars($item[$key]));
                $item[$key] = $element;
            }

            $errors = $manager->validation($item);

            // if validation is ok, insert and redirection
            if (empty($errors)) {
                $manager->update($item);
                header('Location:/admin');
                return null;
            } else {
                $data = [];
                $data[$manager::TABLE] = $itemSave;
                $data['errors'] = $errors;
                return $this->twig->render('Item/edit' . $path, $data);
            }
        }
        return $this->twig->render('Item/edit' . $path, [$manager::TABLE => $item]);
    }

    /**
     * Delete a specific item : item will be choosed in this method between Tag / Menu / Cook / booking
     */
    public function delete(string $table): void
    {
        //Looking for the correct table
        $manager = $this->chooseTable($table);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = trim($_POST['id']);
            $manager->delete((int)$id);
            header('Location:/admin');
        }
    }
}
