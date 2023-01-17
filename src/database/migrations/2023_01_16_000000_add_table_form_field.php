<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableFormField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_field', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('form_id')->nullalble();
            $table->integer('field_id')->nullable();
            $table->string('required_filed')->nullable();
            $table->tinyInteger('unique_field');
            $table->string('label_filed')->nullable();
            $table->string('values')->nullable();
            $table->tinyInteger('sorting')->nullable();
            $table->tinyInteger('active')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('form_field');
    }
}
