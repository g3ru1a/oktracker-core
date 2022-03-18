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
            $table->mediumText('title');
            $table->mediumText('clean_title')->nullable();
            $table->string('isbn_10')->nullable();
            $table->string('isbn_13')->nullable();
            $table->string('publish_date')->nullable();
            $table->integer('pages')->nullable();
            $table->string('cover_url')->default('/missing_cover.png');
            $table->integer('series_id')->nullable();
            $table->float('volume_number')->nullable();
            
            $table->longText("synopsis")->nullable();
            $table->mediumText("authors")->nullable();
            $table->string("language")->nullable();
            $table->mediumText("publisher")->nullable();
            $table->string('binding')->default('paperback');

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
