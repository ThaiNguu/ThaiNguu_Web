<?php

namespace App\View\Components;

use App\Models\Menu;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FooterMenuItem extends Component
{
    public $row_menu = null;

    public function __construct($rowmenu)
    {
        $this->row_menu = $rowmenu;
    }

    public function render(): View|Closure|string
    {
        $menu = $this->row_menu;

        $args_footermenu_sub = [
            ['status', '=', 1],
            ['position', '=', 'footermenu'],
            ['parent_id', '=', $menu->id]
        ];

        $list_menu_sub = Menu::where($args_footermenu_sub)
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('components.footer-menu-item', compact('menu', 'list_menu_sub'));
    }
}
