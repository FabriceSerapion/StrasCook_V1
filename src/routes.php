<?php

// list of accessible routes of your application, add every new route here
// key : route to match
// values : 1. controller name
//          2. method name
//          3. (optional) array of query string keys to send as parameter to the method
// e.g route '/item/edit?id=1' will execute $itemController->edit(1)

use App\Controller\MenusController;

return [
    '' => ['HomeController', 'index',],
    'booking' => ['BookingController', 'booking', ['adress', 'date', 'hour', 'benefit']],
    'bookingMenu' => ['BookingController', 'bookingMenu', ['adress', 'date', 'hour', 'benefit', 'idMenu']],
    'bookingValidation' => ['BookingController', 'bookingValidation', ['adress', 'date', 'hour', 'benefit', 'idMenu']],
    'about' => ['AboutController', 'about'],
    'menus' => ['MenusController', 'indexMenus'],
    'menus/show' => ['MenusController', 'showMenus', ['tag']],
    'lessons' => ['LessonsController', 'lessons'],
    'gifts' => ['GiftsController', 'gifts'],
    'user' => ['UserController', 'userConnect'],
    'basket' => ['BasketController', 'basket'],
    'admin' => ['AdminController', 'indexAdmin'],
    'items/edit' => ['AdminController', 'edit', ['id', 'table']],
    'items/show' => ['AdminController', 'show', ['id'], ['table']],
    'items/add' => ['AdminController', 'add', ['table']],
    'items/delete' => ['AdminController', 'delete', ['table']],
    'login' => ['UserController', 'login'],
    'signup' => ['UserController', 'signup'],
];
