<?php

namespace App\Livewire\Partials;

use App\Helpers\CartManagement;
use Livewire\Attributes\On;
use Livewire\Component;

class Navbar extends Component
{
    public $totalCount = 0;

    public function mount()
    {
        $this->totalCount = count(CartManagement::getCartItemsFromCookie());
    }

    #[On('update-cart-count')]
    public function updateCartCount($totalCount)
    {
        $this->totalCount = $totalCount;
    }

    public function render()
    {
        return view('livewire.partials.navbar');
    }
}
