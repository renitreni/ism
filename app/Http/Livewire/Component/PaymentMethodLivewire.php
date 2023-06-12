<?php

namespace App\Http\Livewire\Component;

use App\PaymentMethod;
use Livewire\Component;

class PaymentMethodLivewire extends Component
{
    public $payment;
    public $paymentList;

    protected $rules = [
        'payment' => 'required|unique:payment_methods,name'
    ];

    public function mount()
    {
        $this->paymentList = PaymentMethod::all()->toArray();
    }

    public function render()
    {
        return view('livewire.component.payment-method-livewire');
    }

    public function store()
    {
        $this->validate();

        PaymentMethod::create(['name' => $this->payment]);

        $this->paymentList = PaymentMethod::all()->toArray();
    }

    public function destroy($id)
    {
        PaymentMethod::destroy($id);

        $this->paymentList = PaymentMethod::all()->toArray();
    }
}
