<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $this->authorize('view_list', Report::class);

        $reports = Report::where('completed', false);
        if(auth()->user()->role_id == Role::DATA_ANALYST){
            $reports = $reports->where('assignee_id', auth()->user()->id);
        }
        $reports = $reports->paginate(15);
        $available_reports = Report::where('completed', false)->where('assignee_id', null)->count();
        return view('pages.reports.index', [
            'reports' => $reports,
            'available_reports' => $available_reports
        ]);
    }

    public function take_batch($batch_size)
    {
        $this->authorize('take_batch', Report::class);

        if($batch_size > 20) $batch_size = 20;
        $reports = Report::where('completed', false)->where('assignee_id', null)->take($batch_size);
        $reports->update(['assignee_id' => Auth::user()->id]);
        return redirect(route('reports.index'));
    }

    public function remove_assignee(Report $report){
        $this->authorize('complete', $report);

        $report->assignee_id = null;
        $report->save();
        return redirect()->back();
    }

    public function complete(Report $report)
    {
        $this->authorize('complete', $report);

        $report->completed = true;
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
