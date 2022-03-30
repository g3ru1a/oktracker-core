<?php

namespace App\Http\Livewire;

use App\Models\BookType;
use Livewire\Component;

class BookTypeSelector extends Component
{
    public $types;
    public $typeInput;
    public $modalOpen = false;
    public $givenType;

    public function addType(){
        if($this->typeInput != ''){
            BookType::create([
                'name'=>$this->typeInput
            ]);
            $this->types = BookType::all();
        }
    }

    public function deleteType($id){
        BookType::find($id)->delete();
        $this->types = BookType::all();
    }

    public function render()
    {
        $this->types = BookType::all();
        return view('livewire.book-type-selector');
    }

    public function toggleModal(){
        $this->modalOpen = !$this->modalOpen;
    }
}
