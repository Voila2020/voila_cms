<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if the table already exists
        if (!Schema::hasTable('fields')) {
            Schema::create('fields', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title')->nullable();
                $table->tinyInteger('multi')->nullable();
            });
        } else {
            // Check if each column exists, and create it if not
            if (!Schema::hasColumn('fields', 'title')) {
                Schema::table('fields', function (Blueprint $table) {
                    $table->string('title')->nullable();
                });
            }
            if (!Schema::hasColumn('fields', 'multi')) {
                Schema::table('fields', function (Blueprint $table) {
                    $table->tinyInteger('multi')->nullable();
                });
            }
            // You can add more checks for additional columns if needed
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('fields');
    }
}
