<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(){
        $reports = Report::all()->take(15);
        return view('pages.reports.index', [
            'reports' => $reports
        ]);
    }

    public function destroy(Report $report){
        $report->delete();
        return redirect(route('reports.index'));
    }
}
