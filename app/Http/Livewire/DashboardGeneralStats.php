<?php

namespace App\Http\Livewire;

use App\Models\Book;
use App\Models\DailyStatsSnapshot;
use App\Models\Report;
use App\Models\Series;
use Livewire\Component;

class DashboardGeneralStats extends Component
{
    public $newBooksThisMonth = 0, $newSeriesThisMonth = 0, $newReportsThisMonth = 0;
    public $bookPercDiff = 0, $seriesPercDiff = 0, $reportsPercDiff = 0;

    public $dayDiff = 30, $prettyDayDiff = 'last month';

    public function render()
    {
        $this->newBooksThisMonth = Book::where('created_at', '>', now()->subDays($this->dayDiff)->endOfDay())->count();
        $this->newSeriesThisMonth = Series::where('created_at', '>', now()->subDays($this->dayDiff)->endOfDay())->count();
        $this->newReportsThisMonth = Report::withTrashed()->where('created_at', '>', now()->subDays($this->dayDiff)->endOfDay())->count();

        $this->bookPercDiff = $this->calculateBookDiff();
        $this->seriesPercDiff = $this->calculateSeriesDiff();
        $this->reportsPercDiff = $this->calculateReportDiff();

        $this->setPrettyDayDiff();

        return view('livewire.dashboard-general-stats');
    }

    private function setPrettyDayDiff()
    {
        switch ($this->dayDiff) {
            case 1:
                $this->prettyDayDiff = 'since yesterday';
                break;
            case 7:
                $this->prettyDayDiff = 'this week';
                break;
            case 30:
                $this->prettyDayDiff = 'this month';
                break;
            case 60:
                $this->prettyDayDiff = 'this quarter';
                break;
            case 90:
                $this->prettyDayDiff = 'this half-year';
                break;
            case 365:
                $this->prettyDayDiff = 'this year';
                break;
        }
    }

    private function calculateBookDiff()
    {
        $original = DailyStatsSnapshot::getBooksCountPastDays($this->dayDiff);
        $increase = true;
        $diff = 0;
        if ($original > $this->newBooksThisMonth) {
            $increase = false;
            $diff = $original - $this->newBooksThisMonth;
        } else {
            $diff = $this->newBooksThisMonth - $original;
        }
        return ($diff / max(($original), 1)) * 100 * ($increase ? 1 : -1);
    }

    private function calculateSeriesDiff()
    {
        $original = DailyStatsSnapshot::getSeriesCountPastDays($this->dayDiff);
        $increase = true;
        $diff = 0;
        if ($original > $this->newSeriesThisMonth) {
            $increase = false;
            $diff = $original - $this->newSeriesThisMonth;
        } else {
            $diff = $this->newSeriesThisMonth - $original;
        }
        return ($diff / max(($original), 1)) * 100 * ($increase ? 1 : -1);
    }

    private function calculateReportDiff()
    {
        $original = DailyStatsSnapshot::getReportsCountPastDays($this->dayDiff);
        $increase = true;
        $diff = 0;
        if ($original > $this->newReportsThisMonth) {
            $increase = false;
            $diff = $original - $this->newReportsThisMonth;
        } else {
            $diff = $this->newReportsThisMonth - $original;
        }
        return ($diff / max(($original), 1)) * 100 * ($increase ? 1 : -1);
    }
}
