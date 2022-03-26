<?php

namespace App\Http\Livewire;

use App\Models\Book;
use Livewire\Component;
use Livewire\WithPagination;

class BookTableList extends Component
{
    use WithPagination;

    public $lookup, $count=15;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $lookupST = htmlspecialchars($this->lookup);
        if($lookupST != "") {
            $lookupJ = implode("%",explode(" ", $lookupST));
            $lookupJ = '%'.$lookupJ.'%';
            $books = Book::whereRaw("CONCAT_WS(',', `clean_title`, `volume_number`) LIKE ?", [$lookupJ])
                ->orderBy('updated_at', 'desc')->paginate($this->count);
        }else{
            $books = Book::orderBy('updated_at', 'desc')->paginate($this->count);
        }

        return view('livewire.book-table-list',[
            'books' => $books
        ]);
    }
}
