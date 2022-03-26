<?php

namespace App\Http\Livewire;

use App\Models\BookKind;
use Livewire\Component;

class BookKindSelector extends Component
{
    public $kinds;
    public $kindInput;
    public $modalOpen = false;
    public $givenKind;

    public function addKind(){
        if($this->kindInput != ''){
            BookKind::create([
                'name'=>$this->kindInput
            ]);
            $this->kinds = BookKind::all();
        }
    }

    public function deleteKind($id){
        BookKind::find($id)->delete();
        $this->kinds = BookKind::all();
    }

    public function render()
    {
        $this->kinds = BookKind::all();
        return view('livewire.book-kind-selector');
    }

    public function toggleModal(){
        $this->modalOpen = !$this->modalOpen;
    }
}
