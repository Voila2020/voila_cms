<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // site_labels table
        Schema::create('site_labels', function (Blueprint $table) {
            $table->increments('id'); // auto increment primary key
            $table->string('label_key', 255);
            $table->tinyInteger('active')->default(1);
            $table->integer('sorting')->nullable();
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_labels');
    }
}
