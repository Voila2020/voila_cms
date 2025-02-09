<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_en', 255)->nullable();
            $table->string('name_ar', 255)->nullable();
            $table->integer('sorting')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->timestamps();
            $table->string('link', 255)->nullable();
            $table->unsignedBigInteger('page_id')->nullable();
            $table->string('type', 255)->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
