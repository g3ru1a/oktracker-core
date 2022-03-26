<?php

namespace App\Http\Livewire;

use App\Models\Series;
use Livewire\Component;
use Livewire\WithPagination;

class SeriesTableList extends Component
{
    use WithPagination;

    public $lookup, $count = 15;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $series = Series::where('title', 'like', '%' . $this->lookup . '%')
            ->orderBy('updated_at', 'desc')->paginate($this->count);
        return view('livewire.series-table-list', [
            'series' => $series
        ]);
    }
}
