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
        $books = Book::where('title', 'like', '%'.$this->lookup.'%')
            ->orderBy('updated_at', 'desc')->paginate($this->count);

        return view('livewire.book-table-list',[
            'books' => $books
        ]);
    }
}
