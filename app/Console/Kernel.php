<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Models\Book;
use App\Models\DailyStatsSnapshot;
use App\Models\Report;
use App\Models\Series;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $newBooksToday = Book::where("created_at", ">", Carbon::now()->subDay())->where("created_at", "<", Carbon::now())->count();
            $newSeriesToday = Series::where("created_at", ">", Carbon::now()->subDay())->where("created_at", "<", Carbon::now())->count();
            $newReportsToday = Report::where("created_at", ">", Carbon::now()->subDay())->where("created_at", "<", Carbon::now())->count();

            Log::info('Data snapshot: '. $newBooksToday. ', '. $newSeriesToday . ', '. $newReportsToday);
            DailyStatsSnapshot::create([
                'new_series' => $newSeriesToday,
                'new_books' => $newBooksToday,
                'new_reports' => $newReportsToday,
            ]);
        })->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
