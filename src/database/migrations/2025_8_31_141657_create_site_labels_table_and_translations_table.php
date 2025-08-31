<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteLabelsAndTranslationsTables extends Migration
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

        // site_label_translations table
        Schema::create('site_label_translations', function (Blueprint $table) {
            $table->increments('id'); // auto increment primary key
            $table->text('label_value')->nullable();
            $table->string('locale', 191);
            $table->unsignedInteger('site_label_id');

            $table->unique(['locale', 'site_label_id'], 'site_label_translations_locale_site_label_id_unique');
            $table->index('site_label_id', 'site_label_translations_site_label_id_foreign');
            $table->index('locale', 'site_label_translations_locale_index');

            // Foreign key relation
            $table->foreign('site_label_id')
                ->references('id')->on('site_labels')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_label_translations');
        Schema::dropIfExists('site_labels');
    }
}
