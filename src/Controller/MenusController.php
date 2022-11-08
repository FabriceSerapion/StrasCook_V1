<?php

namespace App\Controller;

use App\Model\MenuManager;
use App\Model\TagManager;

class MenusController extends AbstractController
{
    /**
     * Show all informations --> menus with their tags linked
     */
    public function indexMenus(): string
    {
        $menuManager = new MenuManager();
        $tagManager = new TagManager();
        $menus = $menuManager->selectAll();
        foreach ($menus as $idx => $menu) {
            $tagsFromMenu = $tagManager->selectAllTagsFromMenu($menu['id']);
            $menus[$idx]["tags"] = $tagsFromMenu;
        }
        $data = ['menus' => $menus];

        //Verify if user is connected
        if (!empty($_SESSION) && $_SESSION['authed']) {
            $username = $_SESSION["username"];
            $data['username'] = $username;
        }

        return $this->twig->render('Pages/menus.html.twig', $data);
    }

    /**
     * Show informations --> menus with their tags linked, search by tag
     */
    public function showMenus(string $tag): string
    {
        $menuManager = new MenuManager();
        $tagManager = new TagManager();

        //Validation --> tag must be string
        $tagValidated = trim(htmlspecialchars($tag));
        $menus = $menuManager->selectAllFromTag($tagValidated);

        foreach ($menus as $idx => $menu) {
            $tagsFromMenu = $tagManager->selectAllTagsFromMenu($menu['id']);
            $menus[$idx]["tags"] = $tagsFromMenu;
        }

        $data = ['menus' => $menus];
        $data['tag'] = $tag;

        //Verify if user is connected
        if (!empty($_SESSION) && $_SESSION['authed']) {
            $username = $_SESSION["username"];
            $data['username'] = $username;
        }

        return $this->twig->render('Pages/menus.html.twig', $data);
    }
}
