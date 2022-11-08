<?php

namespace App\Controller;

use App\Model\MenuManager;
use App\Model\TagManager;

class HomeController extends AbstractController
{
    /**
     * Display home page
     */
    public function index(): string
    {
        //Verify if user is connected
        $data = array();
        if (!empty($_SESSION) && $_SESSION['authed']) {
            $username = $_SESSION["username"];
            $data['username'] = $username;
        }
        $menuManager = new MenuManager();
        $tagManager = new TagManager();
        $menus = $menuManager->selectAll(limit: 3);
        foreach ($menus as $idx => $menu) {
            $tagsFromMenu = $tagManager->selectAllTagsFromMenu($menu['id']);
            $menus[$idx]["tags"] = $tagsFromMenu;
        }
        $data['menus'] = $menus;

        return $this->twig->render('Home/index.html.twig', $data);
    }
}
