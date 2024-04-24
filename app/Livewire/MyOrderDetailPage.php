<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Orders Detail Page - E-commerce')]
class MyOrderDetailPage extends Component
{
    public $order;

    public function mount($order_id)
    {
        $this->order = Order::with(['items.product', 'address'])
            ->where('id', $order_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.my-order-detail-page', [
            'order' => $this->order
        ]);
    }
}
