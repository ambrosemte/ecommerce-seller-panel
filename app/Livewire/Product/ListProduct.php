<?php

namespace App\Livewire\Product;

use Livewire\Attributes\Layout;
use Livewire\Component;

class ListProduct extends Component
{

    #[Layout('components.layouts.app',['title'=>"List Product"])]
    public function render()
    {
        return view('livewire.product.list-product');
    }
}
