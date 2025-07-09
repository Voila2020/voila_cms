<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if the table already exists
        if (!Schema::hasTable('languages')) {
            Schema::create('languages', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('code');
                $table->string('direction')->default('ltr');
                $table->tinyInteger('active')->nullable();
                $table->tinyInteger('default')->nullable();
                $table->timestamps();
            });
        } else {
            // Check if each column exists, and create it if not
            if (!Schema::hasColumn('languages', 'name')) {
                Schema::table('languages', function (Blueprint $table) {
                    $table->string('name');
                });
            }
            if (!Schema::hasColumn('languages', 'code')) {
                Schema::table('languages', function (Blueprint $table) {
                    $table->string('code');
                });
            }
            if (!Schema::hasColumn('languages', 'active')) {
                Schema::table('languages', function (Blueprint $table) {
                    $table->tinyInteger('active')->nullable();
                });
            }
            if (!Schema::hasColumn('languages', 'default')) {
                Schema::table('languages', function (Blueprint $table) {
                    $table->tinyInteger('default')->nullable();
                });
            }
            if (!Schema::hasColumn('languages', 'direction')) {
                Schema::table('languages', function (Blueprint $table) {
                    $table->string('direction')->default('ltr');
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
        Schema::drop('languages');
    }
}
