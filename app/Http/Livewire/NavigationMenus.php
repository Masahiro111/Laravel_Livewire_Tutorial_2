<?php

namespace App\Http\Livewire;

use App\Models\NavigationMenu;
use Livewire\Component;

class NavigationMenus extends Component
{
    public $modelId;
    public $label;
    public $slug;
    public $sequence = 1;
    public $type = 'SlideNav';

    public function read()
    {
        return NavigationMenu::paginate(5);
    }

    public function render()
    {
        return view('livewire.navigation-menus', [
            'data' => $this->read(),
        ]);
    }
}
