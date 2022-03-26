<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyStatsSnapshot extends Model
{
    use HasFactory;

    protected $fillable = ['new_series', 'new_books', 'new_reports', 'new_users', 'new_collections', 'new_collection_items'];

    public static function getBooksCountPastDays($days = 1){
        $entries = DailyStatsSnapshot::where('created_at', '>', now()->subDays($days+1)->endOfDay())
            ->where("created_at", "<", now()->subDays(1))->get();
        $total = 0;
        foreach($entries as $e){
            $total += $e->new_books;
        }
        return $total;
    }
    public static function getSeriesCountPastDays($days = 1)
    {
        $entries = DailyStatsSnapshot::where('created_at', '>', now()->subDays($days+1)->endOfDay())
            ->where("created_at", "<", now()->subDays(1))->get();
        $total = 0;
        foreach ($entries as $e) {
            $total += $e->new_series;
        }
        return $total;
    }
    public static function getReportsCountPastDays($days = 1)
    {
        $entries = DailyStatsSnapshot::where('created_at', '>', now()->subDays($days+1)->endOfDay())
            ->where("created_at", "<", now()->subDays(1))->get();
        $total = 0;
        foreach ($entries as $e) {
            $total += $e->new_reports;
        }
        return $total;
    }
}
