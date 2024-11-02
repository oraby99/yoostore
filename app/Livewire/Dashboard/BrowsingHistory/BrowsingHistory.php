<?php

namespace App\Livewire\Dashboard\BrowsingHistory;

use App\Models\ProductHistory;
use Livewire\Component;

class BrowsingHistory extends Component
{
    public $products;
    public $searchTerm = '';
    public $selectedDate = null;
    public function mount()
    {
        $this->fetchProducts();
    }


    public function fetchProducts()
    {
        $query = ProductHistory::where('user_id', auth()->user()->id);

        if ($this->searchTerm) {
            $query->whereHas('product', function ($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%');
            });
        }

        if ($this->selectedDate) {
            $query->whereDate('created_at', $this->selectedDate);
        }

        $this->products = $query->get();
    }

    public function updatedSearchTerm()
    {
        $this->fetchProducts();
    }

    public function updatedSelectedDate()
    {
        $this->fetchProducts();
    }
    public function render()
    {

        return view('livewire.dashboard.browsing-history.browsing-history');
    }
}
