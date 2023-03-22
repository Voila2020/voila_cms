<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCmsSeo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_seo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title_en')->nullable();
            $table->string('title_ar')->nullable();
            $table->longText('description_en')->nullable();
            $table->longText('description_ar')->nullable();
            $table->longText('keywords_en')->nullable();
            $table->longText('keywords_ar')->nullable();
            $table->string('author_en')->nullable();
            $table->string('author_ar')->nullable();
            $table->string('model')->nullable();
            $table->integer('model_id')->nullable();
            $table->string('image')->nullable();
            $table->string('language')->nullable();
            $table->integer('active');
            $table->integer('sorting')->nullable();
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
        Schema::drop('cms_seo');
    }
}
