<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyStatsSnapshotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_stats_snapshots', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('new_series');
            $table->bigInteger('new_books');
            $table->bigInteger('new_reports');
            $table->bigInteger('new_users')->default(0)->nullable();
            $table->bigInteger('new_collections')->default(0)->nullable();
            $table->bigInteger('new_collection_items')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_stats_snapshots');
    }
}
