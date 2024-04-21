<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Stripe\Checkout\Session;
use Stripe\Stripe;

#[Title('Success Page - E-commerce')]
class SuccessPage extends Component
{
    #[Url]
    public $session_id;

    protected $stripe;

    public function __construct(Stripe $stripe)
    {
        $this->stripe = $stripe;
    }

    public function render()
    {
        $lastOrder = Order::with('address')->where('user_id', auth()->user()->id)->latest()->first();

        if($this->session_id){
            $this->handlePayment($lastOrder);
        }

        return view('livewire.success-page', [
            'order' => $lastOrder
        ]);
    }

    protected function handlePayment($order)
    {
        $this->stripe::setApiKey(env('STRIPE_SECRET'));
        $session_info = Session::retrieve($this->session_id);

        if($session_info->payment_status !== 'paid'){
            $order->payment_status = 'failed';
            $order->save();

            return redirect()->route('cancel');
        }

        $order->payment_status = 'paid';
        $order->save();
    }
}
