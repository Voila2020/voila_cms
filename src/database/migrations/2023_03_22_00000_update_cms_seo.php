<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCmsSeo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cms_seo', function (Blueprint $table) {
            # drop
            $table->dropColumn('title_en')->nullable();
            $table->dropColumn('title_ar')->nullable();
            $table->dropColumn('description_en')->nullable();
            $table->dropColumn('description_ar')->nullable();
            $table->dropColumn('keywords_en')->nullable();
            $table->dropColumn('keywords_ar')->nullable();
            $table->dropColumn('author_en')->nullable();
            $table->dropColumn('author_ar')->nullable();
            $table->dropColumn('model_id')->nullable();
            $table->dropColumn('model')->nullable();
            # add
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->longText('keywords')->nullable();
            $table->string('author')->nullable();
            $table->string('language')->nullable();
            $table->string('page')->nullable();
            $table->integer('page_id')->nullable();
        });
    }
}
