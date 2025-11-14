<?php

namespace App\Livewire\Product;

use Livewire\Attributes\Layout;
use Livewire\Component;

class ListProduct extends Component
{

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.product.list-product');
    }
}
