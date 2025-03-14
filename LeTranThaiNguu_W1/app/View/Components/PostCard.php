<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PostCard extends Component
{
    public $postitem;

    public function __construct($postitem)
    {
        $this->postitem = $postitem;
    }

    public function render()
    {
        return view('components.post-card');
    }
}
