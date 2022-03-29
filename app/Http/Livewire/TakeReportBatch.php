<?php

namespace App\Http\Livewire;

use App\Models\Report;
use Livewire\Component;

class TakeReportBatch extends Component
{
    public $available_reports = 0;
    public $batch_size = 20;

    public $checked_collect_data = false;
    public $checked_user_reported = false;

    public $collect_data_count = 0;
    public $user_reported_count = 0;

    public $priority_severe_count = 0;
    public $priority_important_count = 0;
    public $priority_minor_count = 0;
    public $priority_clean_count = 0;
    public $priority_any_count = 0;

    public function updatedCheckedCollectData(){
        $this->updateCount();
    }

    public function updatedUserReportedCount(){
        $this->updateCount();
    }

    public function updateCount(){

        $priority_prep = Report::where('status', Report::STATUS_CREATED);

        if($this->checked_user_reported){
            $priority_prep = $priority_prep->whereNotNull('reporter_id');
        }

        $this->priority_severe_count = $priority_prep->clone()->where('priority', '>=', Report::PRIORITY_TRESHOLDS["SEVERE_ISSUES"])
            ->where('priority', '<', Report::PRIORITY_TRESHOLDS["COLLECT_DATA"])->count();
        $this->priority_important_count = $priority_prep->clone()->where('priority', '>=', Report::PRIORITY_TRESHOLDS["IMPORTANT_ISSUES"])
            ->where('priority', '<', Report::PRIORITY_TRESHOLDS["SEVERE_ISSUES"])->count();
        $this->priority_minor_count = $priority_prep->clone()->where('priority', '>=', Report::PRIORITY_TRESHOLDS["MINOR_ISSUES"])
            ->where('priority', '<', Report::PRIORITY_TRESHOLDS["IMPORTANT_ISSUES"])->count();
        $this->priority_clean_count = $priority_prep->clone()->where('priority', '>=', Report::PRIORITY_TRESHOLDS["AWAITING_REVIEW"])
            ->where('priority', '<', Report::PRIORITY_TRESHOLDS["MINOR_ISSUES"])->count();
        $this->priority_any_count = $priority_prep->clone()->where('priority', '<', Report::PRIORITY_TRESHOLDS["COLLECT_DATA"])->count();

    }

    public function render()
    {
        $this->available_reports = Report::whereIn('status', [Report::STATUS_CREATED, Report::STATUS_ASSIGNED])->where('assignee_id', null)->count();

        $this->collect_data_count = Report::where('status', Report::STATUS_CREATED)->where('priority', Report::PRIORITY_TRESHOLDS["COLLECT_DATA"])->count();
        $this->user_reported_count = Report::where('status', Report::STATUS_CREATED)->whereNotNull('reporter_id')->count();

        $this->updateCount();
        return view('livewire.take-report-batch');
    }
}
