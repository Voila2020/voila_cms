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
        if (!Schema::hasTable('form_field')) {
            Schema::create('form_field', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('form_id')->nullable();
                $table->integer('field_id')->nullable();
                $table->string('required_filed')->nullable();
                $table->tinyInteger('unique_field');
                $table->string('label_filed')->nullable();
                $table->string('values')->nullable();
                $table->tinyInteger('sorting')->nullable();
                $table->tinyInteger('active')->nullable();
            });
        } else {
            // Check if each column exists, and create it if not
            if (!Schema::hasColumn('form_field', 'form_id')) {
                Schema::table('form_field', function (Blueprint $table) {
                    $table->integer('form_id')->nullable();
                });
            }
            if (!Schema::hasColumn('form_field', 'field_id')) {
                Schema::table('form_field', function (Blueprint $table) {
                    $table->integer('field_id')->nullable();
                });
            }
            if (!Schema::hasColumn('form_field', 'required_filed')) {
                Schema::table('form_field', function (Blueprint $table) {
                    $table->string('required_filed')->nullable();
                });
            }
            if (!Schema::hasColumn('form_field', 'unique_field')) {
                Schema::table('form_field', function (Blueprint $table) {
                    $table->tinyInteger('unique_field');
                });
            }
            if (!Schema::hasColumn('form_field', 'label_filed')) {
                Schema::table('form_field', function (Blueprint $table) {
                    $table->string('label_filed')->nullable();
                });
            }
            if (!Schema::hasColumn('form_field', 'values')) {
                Schema::table('form_field', function (Blueprint $table) {
                    $table->string('values')->nullable();
                });
            }
            if (!Schema::hasColumn('form_field', 'sorting')) {
                Schema::table('form_field', function (Blueprint $table) {
                    $table->tinyInteger('sorting')->nullable();
                });
            }
            if (!Schema::hasColumn('form_field', 'active')) {
                Schema::table('form_field', function (Blueprint $table) {
                    $table->tinyInteger('active')->nullable();
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
        Schema::drop('form_field');
    }
}
