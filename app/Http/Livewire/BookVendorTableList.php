<?php

namespace App\Http\Livewire;

use App\Models\BookVendor;
use Livewire\Component;
use Livewire\WithPagination;

class BookVendorTableList extends Component
{
    use WithPagination;

    public $lookup, $count = 15;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $vendors = BookVendor::where('name', 'like', '%' . $this->lookup . '%')
            ->orderBy('updated_at', 'desc')->paginate($this->count);

        return view('livewire.book-vendor-table-list', [
            'vendors' => $vendors
        ]);
    }
}
