<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Report;
use App\Models\Role;

class ReportsTable extends Component
{
    protected $reports = null;

    public function render()
    {
        if(auth()->user()->role_id == Role::DATA_ANALYST){
            $this->reports = Report::whereIn('status', [Report::STATUS_CREATED, Report::STATUS_ASSIGNED])->where('assignee_id', auth()->user()->id);
        }else if(auth()->user()->role_id == Role::ADMIN){
            $this->reports = Report::whereNotIn('status', [Report::STATUS_COMPLETED]);
        }else{
            return;
        }
        $this->reports = $this->reports->orderBy("priority", "desc")->paginate(15);

        return view('livewire.reports-table', [
            'reports' => $this->reports,
        ]);
    }
}
