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

        return $this->twig->render('Pages/menus.html.twig', $data);
    }

    /**
     * Show informations for a specific menu
     */

    // TODO pop up javascript in twig in order to show one menu
    public function showOneMenu(int $id): string
    {
        $menuManager = new MenuManager();
        $tagManager = new TagManager();
        // TODO validation for id selected by user
        $menu = $menuManager->selectOneById($id);
        $tagsFromMenu = $tagManager->selectAllTagsFromMenu($menu[0]);
        array_push($menu, $tagsFromMenu);

        return $this->twig->render('Menu/show.html.twig', ['menu' => $menu]);
    }
}
