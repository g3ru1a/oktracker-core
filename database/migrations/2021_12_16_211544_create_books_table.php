<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('isbn_10')->nullable();
            $table->string('isbn_13')->nullable();
            $table->string('publish_date')->nullable();
            $table->integer('pages')->nullable();
            $table->string('cover_url')->default('/missing_cover.png');
            $table->integer('series_id')->nullable();
            $table->float('volume_number')->nullable();
            $table->boolean('oneshot')->default(false);
            $table->boolean('new')->default(true);
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
        Schema::dropIfExists('books');
    }
}
