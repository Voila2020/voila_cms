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
        // Check if the table already exists
        Schema::create('custom_blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('custom_block_data')->nullable();
            $table->text('blockID')->nullable();
            $table->string('block_name')->nullable();
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
        Schema::drop('custom_blocks');
    }
}
