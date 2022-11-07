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
     * Add a new item : item will be choosed in this method between Tag / Menu / Cook / booking
     */
    public function add(string $table): ?string
    {
        //Looking for the correct table
        $manager = $this->chooseTable($table);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $item = array_map('trim', $_POST);
            foreach ($item as $key => $element) {
                $element = trim(htmlspecialchars($item[$key]));
                $item[$key] = $element;
            }

            //Validation for the item
            $errors = $manager->validation($item);

            // if validation is ok, insert and redirection
            if (empty($errors)) {
                $manager->insert($item);
                //Specific for a menu --> modifying tags linked
                if ($table == 0) {
                    $this->specificInsertion($item);
                }
                header('Location:/admin');
                return null;
            } else {
                //Show errors
                $data = [];
                $data[$manager::TABLE] = $item;
                $data['errors'] = $errors;
                return $this->twig->render('Item/add' . $manager::PATH, $data);
            }
        }
        return $this->twig->render('Item/add' . $manager::PATH);
    }

    /**
     * Edit a specific item : item will be choosed in this method between Tag / Menu / Cook / booking
     */
    public function edit(int $id, string $table): ?string
    {
        //Looking for the correct table
        $manager = $this->chooseTable($table);

        //Validation --> id must be numeric
        if (is_numeric($id)) {
            //Finding the correct item
            $item = $manager->selectOneById($id);

            //Specific for a menu --> finding all tags linked
            if ($table == 0) {
                $tagManager = new TagManager();
                $tagsFromMenu = $tagManager->selectAllTagsFromMenu($id);
                $allTags = '';
                foreach ($tagsFromMenu as $tags) {
                    $allTags = $allTags . ' ' . $tags['name_tag'];
                }
                $allTags = trim($allTags);
                $item["tags"] = $allTags;
            }
        }

        //Saving the original item before modifications
        $itemSave = $item;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $item = array_map('trim', $_POST);
            foreach ($item as $key => $element) {
                $element = trim(htmlspecialchars($item[$key]));
                $item[$key] = $element;
            }

            //Validation for the item
            $errors = $manager->validation($item);

            // if validation is ok, update and redirection
            if (empty($errors)) {
                $manager->update($item, $itemSave);
                //Specific for a menu --> modifying tags linked
                if ($table == 0) {
                    $this->specificUpdate($item, $itemSave);
                }
                header('Location:/admin');
                return null;
            } else {
                //Show errors
                $data = [];
                $data[$manager::TABLE] = $itemSave;
                $data['errors'] = $errors;
                return $this->twig->render('Item/edit' . $manager::PATH, $data);
            }
        }
        return $this->twig->render('Item/edit' . $manager::PATH, [$manager::TABLE => $item]);
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

    /**
     * Creating menu and link tags to menu
     */
    public function specificInsertion(array $newMenu): void
    {
        $tagManager = new TagManager();
        $menuManager = new MenuManager();
        $newTags = explode(' ', $newMenu['tags']);
        //Validation --> newMenu['name_menu'] must be string
        $nameMenuValidated = trim(htmlspecialchars($newMenu['name_menu']));
        $idMenu = $menuManager->selectOneMenuByName($nameMenuValidated);
        foreach ($newTags as $newTag) {
            //Validation --> newTag must be string
            $newTagValidated = trim(htmlspecialchars($newTag));
            $tagExist = $tagManager->selectTagFromName($newTagValidated);
            //Validation --> tag must exist and ids must be numerics
            if ($tagExist && is_numeric($idMenu['id']) && is_numeric($tagExist['id'])) {
                $tagManager->insertTagInMenu($idMenu['id'], $tagExist['id']);
            }
        }
    }

    /**
     * Modifying tags linked in menu
     */
    public function specificUpdate(array $newMenu, array $oldMenu): void
    {
        $newTags = explode(' ', $newMenu['tags']);
        //Compare old tags and new tags
        if ($newMenu['tags'] != $oldMenu['tags']) {
            $oldTags = explode(' ', $oldMenu['tags']);
            //Adding tags if a new tag is found
            foreach ($newTags as $newTag) {
                if (!in_array($newTag, $oldTags)) {
                    $this->findAndInsertion($newTag, $newMenu);
                }
            }
            //Deletind tags if there are not found
            foreach ($oldTags as $oldTag) {
                if (!in_array($oldTag, $newTags)) {
                    $this->findAndDelete($oldTag, $newMenu);
                }
            }
        }
    }

    /**
     * If tag is found and verified, can be added to the menu modified
     */
    public function findAndInsertion(string $newTag, array $newMenu): void
    {
        $tagManager = new TagManager();
        //Validation --> newTag must be string
        $newTagValidated = trim(htmlspecialchars($newTag));
        $tagExist = $tagManager->selectTagFromName($newTagValidated);
        //Validation --> tag must exist and ids must be numerics
        if ($tagExist && is_numeric($newMenu['id']) && is_numeric($tagExist['id'])) {
            $tagManager->insertTagInMenu($newMenu['id'], $tagExist['id']);
        }
    }

    /**
     * If tag is found and verified, can be deleted to the menu modified
     */
    public function findAndDelete(string $oldTag, array $newMenu): void
    {
        $tagManager = new TagManager();
        //Validation --> newTag must be string
        $oldTagValidated = trim(htmlspecialchars($oldTag));
        $tagExist = $tagManager->selectTagFromName($oldTagValidated);
        //Validation --> tag must exist and ids must be numerics
        if ($tagExist && is_numeric($newMenu['id']) && is_numeric($tagExist['id'])) {
            $tagManager->deleteTagInMenu($newMenu['id'], $tagExist['id']);
        }
    }
}
