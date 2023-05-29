<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableForms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if the table already exists
        if (!Schema::hasTable('forms')) {
            Schema::create('forms', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->longText('response')->nullable();
                $table->string('send_to')->nullable();
                $table->string('row_type')->nullable();
                $table->tinyInteger('sorting')->nullable();
                $table->tinyInteger('active');
            });
        } else {
            // Check if each column exists, and create it if not
            if (!Schema::hasColumn('forms', 'name')) {
                Schema::table('forms', function (Blueprint $table) {
                    $table->string('name')->nullable();
                });
            }
            if (!Schema::hasColumn('forms', 'response')) {
                Schema::table('forms', function (Blueprint $table) {
                    $table->longText('response')->nullable();
                });
            }
            if (!Schema::hasColumn('forms', 'send_to')) {
                Schema::table('forms', function (Blueprint $table) {
                    $table->string('send_to')->nullable();
                });
            }
            if (!Schema::hasColumn('forms', 'row_type')) {
                Schema::table('forms', function (Blueprint $table) {
                    $table->string('row_type')->nullable();
                });
            }
            if (!Schema::hasColumn('forms', 'sorting')) {
                Schema::table('forms', function (Blueprint $table) {
                    $table->tinyInteger('sorting')->nullable();
                });
            }
            if (!Schema::hasColumn('forms', 'active')) {
                Schema::table('forms', function (Blueprint $table) {
                    $table->tinyInteger('active');
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
        Schema::drop('forms');
    }
}
