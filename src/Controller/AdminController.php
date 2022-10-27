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
        $menuManager = new MenuManager();
        $tagManager = new TagManager();
        $menus = $menuManager->selectAll();
        $tags = $tagManager->selectAll();
        foreach ($menus as $idx => $menu) {
            $tagsFromMenu = $tagManager->selectAllTagsFromMenu($menu['id']);
            $menus[$idx]["tags"] = $tagsFromMenu;
        }
        $data = ['menus' => $menus];
        $data ['tags'] = $tags;

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
     * Show informations for a specific item
     */
    // public function show(int $id, string $table): string
    // {
    //     $manager = $this->chooseTable($table);
    //     $data = $manager->selectOneById($id);

    //     return $this->twig->render('Admin/admin.html.twig', ['menu' => $data]);
    // }

    /**
     * Add a new item : item will be choosed in this method between Tag / Menu / Cook / booking
     */
    public function add(string $table): ?string
    {
        //Cherche le chemin correct pour déplacer les data en front-end
        $path = $this->chooseReturn($table);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $item = array_map('trim', $_POST);

            // TODO validations (length, format...) AND IF NULL OR NOT

            // if validation is ok, insert and redirection
            $manager = $this->chooseTable($table);
            $manager->insert($item);

            header('Location:/admin');
            return null;
        }
        return $this->twig->render('Item/add' . $path);
    }

    /**
     * Edit a specific item : item will be choosed in this method between Tag / Menu / Cook / booking
     */
    public function edit(int $id, string $table): ?string
    {
        //Cherche la table pour modifier les bonnes data
        $manager = $this->chooseTable($table);
        //Cherche le chemin correct pour déplacer les data en front-end
        $path = $this->chooseReturn($table);

        $item = $manager->selectOneById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $item = array_map('trim', $_POST);

            // TODO validations (length, format...) AND IF NULL OR NOT

            // if validation is ok, update and redirection
            $manager->update($item);

            header('Location:/admin');

            // we are redirecting so we don't want any content rendered
            return null;
        }
        return $this->twig->render('Item/edit' . $path, [$manager::TABLE => $item]);
    }

    /**
     * Delete a specific item : item will be choosed in this method between Tag / Menu / Cook / booking
     */
    public function delete(string $table): void
    {
        //Cherche la table pour modifier les bonnes data
        $manager = $this->chooseTable($table);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = trim($_POST['id']);
            $manager->delete((int)$id);
            header('Location:/admin');
        }
    }
}
