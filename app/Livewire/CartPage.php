<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Cart Page - E-commerce')]
class CartPage extends Component
{
    public $cartItems = [];
    public $grandTotal;

    public function mount()
    {
        $this->cartItems = CartManagement::getCartItemsFromCookie();
        $this->grandTotal = CartManagement::calculateGrandTotal($this->cartItems);
    }

    public function increaseQty($productId)
    {
        $this->cartItems = CartManagement::incrementQuantityToCartItem($productId);
        $this->grandTotal = CartManagement::calculateGrandTotal($this->cartItems);

        $this->dispatch('update-cart-count', totalCount: count($this->cartItems))->to(Navbar::class);
    }

    public function decreaseQty($productId)
    {
        $this->cartItems = CartManagement::decrementQuantityToCartItem($productId);
        $this->grandTotal = CartManagement::calculateGrandTotal($this->cartItems);

        $this->dispatch('update-cart-count', totalCount: count($this->cartItems))->to(Navbar::class);
    }

    public function removeItem($productId)
    {
        $this->cartItems = CartManagement::removeCartItem($productId);
        $this->grandTotal = CartManagement::calculateGrandTotal($this->cartItems);

        $this->dispatch('update-cart-count', totalCount: count($this->cartItems))->to(Navbar::class);
    }

    public function render()
    {
        return view('livewire.cart-page');
    }
}
