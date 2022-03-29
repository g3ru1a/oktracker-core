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
        return view('pages.reports.index');
    }

    public function take_batch(Request $request)
    {
        $this->authorize('take_batch', Report::class);

        $reports = Report::whereNotIn("status", [Report::STATUS_COMPLETED, Report::STATUS_DROPPED]);

        if(isset($request->USER_REPORT)) $reports->whereNotNull("reporter_id");

        if(!isset($request->ANY)){
            $reports->where(function($query) use ($request){
                if(isset($request->SEVERE)) $query->orWhere(function($query) use ($request){
                    $query->where('priority', '>=', Report::PRIORITY_TRESHOLDS["SEVERE_ISSUES"])
                    ->where('priority', '<', Report::PRIORITY_TRESHOLDS["COLLECT_DATA"]);
                });

                if(isset($request->IMPORTANT)) $query->orWhere(function($query) use ($request){
                    $query->where('priority', '>=', Report::PRIORITY_TRESHOLDS["IMPORTANT_ISSUES"])
                    ->where('priority', '<', Report::PRIORITY_TRESHOLDS["SEVERE_ISSUES"]);
                });

                if(isset($request->MINOR)) $query->orWhere(function($query) use ($request){
                    $query->where('priority', '>=', Report::PRIORITY_TRESHOLDS["MINOR_ISSUES"])
                    ->where('priority', '<', Report::PRIORITY_TRESHOLDS["IMPORTANT_ISSUES"]);
                });

                if(isset($request->CLEAN)) $query->orWhere(function($query) use ($request){
                    $query->where('priority', '>=', Report::PRIORITY_TRESHOLDS["AWAITING_REVIEW"])
                    ->where('priority', '<', Report::PRIORITY_TRESHOLDS["MINOR_ISSUES"]);
                });
            });
        }else{
            $reports->where('priority', '<', Report::PRIORITY_TRESHOLDS["COLLECT_DATA"]);
        }

        if(isset($request->COLLECT)) $reports->orWhere('priority', Report::PRIORITY_TRESHOLDS["COLLECT_DATA"]);

        // $query = str_replace(array('?'), array('\'%s\''), $reports->toSql());
        // $query = vsprintf($query, $reports->getBindings());
        // dd($query);

        $reports = $reports->orderBy('priority', 'desc')->take($request->size);

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

    public function drop(Report $report)
    {
        $this->authorize('drop', $report);

        $report->status = Report::STATUS_DROPPED;
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
