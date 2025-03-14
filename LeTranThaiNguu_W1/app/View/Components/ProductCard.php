<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductCard extends Component
{
    public $product;

    /**
     * Create a new component instance.
     *
     * @param  $productitem
     * @return void
     */
    public function __construct($productitem)
    {
        $this->product = $productitem;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('components.product-card', ['product' => $this->product]);
    }
}
