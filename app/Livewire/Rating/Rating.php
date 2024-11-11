<?php

namespace App\Livewire\Rating;


use Livewire\Component;
use App\Models\Rate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
class Rating extends Component
{


   
    public $rating = 0;
    public $productId;

    public function mount()
    {
        $this->productId = url()->current() ? Request::segment(2) : null;

        $rate = Rate::where('product_id', $this->productId)
                    ->where('user_id', Auth::id())
                    ->first();

        if ($rate) {
            $this->rating = $rate->rating;
        }


        // dd($this->productId);
    }


    public function setRating($value)
    {
        // dd($value);
        if (Auth::guest()) {
           session()->flash('error', 'You must be logged in to rate this product.');
        }else{
            $this->rating = $value;
            Rate::updateOrCreate(
                [
                    'product_id' => $this->productId,
                    'user_id' => Auth::id(),
                ],
                [
                    'rate' => $this->rating,
                ]
            );
        }
    }

    public function render()
    {
        return view('livewire.rating.rating');
    }
}
