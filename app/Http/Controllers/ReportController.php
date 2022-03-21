<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Report;
use App\Models\Role;
use App\Models\Series;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{

    public function reportBookInfo(Book $book){
        //TODO: REPORT BOOK LOGIC
    }

    public function reportSeriesInfo(Series $series){
        //TODO: REPORT SERIES LOGIC
    }

    public function index()
    {
        $this->authorize('view_list', Report::class);

        $reports = Report::whereIn('status', [Report::STATUS_CREATED, Report::STATUS_ASSIGNED]);
        if(auth()->user()->role_id == Role::DATA_ANALYST){
            $reports = $reports->where('assignee_id', auth()->user()->id);
        }
        $reports = $reports->paginate(15);
        $available_reports = Report::where('status', [Report::STATUS_CREATED, Report::STATUS_ASSIGNED])->where('assignee_id', null)->count();
        return view('pages.reports.index', [
            'reports' => $reports,
            'available_reports' => $available_reports
        ]);
    }

    public function take_batch($batch_size)
    {
        $this->authorize('take_batch', Report::class);

        if($batch_size > 20) $batch_size = 20;
        $reports = Report::where('status', Report::STATUS_CREATED)->where('assignee_id', null)->take($batch_size);
        $reports->update(['assignee_id' => Auth::user()->id, 'status' => Report::STATUS_ASSIGNED]);
        return redirect(route('reports.index'));
    }

    public function remove_assignee(Report $report){
        $this->authorize('complete', $report);

        $report->assignee_id = null;
        $report->status = Report::STATUS_CREATED;
        $report->save();
        return redirect()->back();
    }

    public function complete(Report $report)
    {
        $this->authorize('complete', $report);

        $report->status = Report::STATUS_COMPLETED;
        $report->save();
        return redirect()->back();
    }

    public function destroy(Report $report)
    {
        $this->authorize('destroy', Report::class);

        $report->delete();
        return redirect()->back();
    }
}
