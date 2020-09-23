<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->date('adverts')->nullable();
            $table->date('submission')->nullable();
            $table->date('notice')->nullable();
            $table->date('contract')->nullable();
            $table->integer('FALLTCOD')->nullable();
            $table->integer('FFUNCCOD')->nullable();
            $table->integer('FFUNDCOD')->nullable();
            $table->integer('idappttype')->nullable();
            $table->integer('idsource')->nullable();
            $table->string('year_')->nullable();
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
        Schema::dropIfExists('schedules');
    }
}
