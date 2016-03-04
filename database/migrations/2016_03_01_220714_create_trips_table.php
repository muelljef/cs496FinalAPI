<?php

//use Illuminate\Database\Schema\Blueprint;
//use Jenssegers\Mongodb\Schema;
use Jenssegers\Mongodb\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('trips', function(Blueprint $collection) {
            $collection::string('title');
            $collection::string('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('trips');

    }
}
