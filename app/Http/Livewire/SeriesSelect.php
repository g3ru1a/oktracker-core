<?php

namespace App\Http\Livewire;

use App\Models\Series;
use Livewire\Component;

class SeriesSelect extends Component
{
    public $lookup, $count=50;

    public $series_id = null;
    public $selected=null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function selectSeries($series_id){
        $this->series_id = $series_id;
        $this->selected = Series::find($series_id);
        $this->lookup = $this->selected->title;
    }

    public function clearSelection(){
        $this->series_id = null;
        $this->selected = null;
        $this->lookup = "";
    }

    public function render()
    {
        if($this->series_id !== null){
            $this->selected = Series::find($this->series_id);
            $this->lookup = $this->selected->title;
        }
        $series = Series::where('title', 'like', '%' . $this->lookup . '%')
            ->orderBy('updated_at', 'desc')->paginate($this->count);

        return view('livewire.series-select',[
            'series' => $series,
            'open_results' => $this->lookup != "" && $this->selected == null,
        ]);
    }
}
