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
            if (Schema::hasColumn('cms_seo', 'title_en')) {
                $table->dropColumn('title_en')->nullable();
            }

            if (Schema::hasColumn('cms_seo', 'title_ar')) {
                $table->dropColumn('title_ar')->nullable();
            }

            if (Schema::hasColumn('cms_seo', 'description_en')) {
                $table->dropColumn('description_en')->nullable();
            }

            if (Schema::hasColumn('cms_seo', 'description_ar')) {
                $table->dropColumn('description_ar')->nullable();
            }

            if (Schema::hasColumn('cms_seo', 'keywords_en')) {
                $table->dropColumn('keywords_en')->nullable();
            }

            if (Schema::hasColumn('cms_seo', 'keywords_ar')) {
                $table->dropColumn('keywords_ar')->nullable();
            }

            if (Schema::hasColumn('cms_seo', 'author_en')) {
                $table->dropColumn('author_en')->nullable();
            }

            if (Schema::hasColumn('cms_seo', 'author_ar')) {
                $table->dropColumn('author_ar')->nullable();
            }

            if (Schema::hasColumn('cms_seo', 'model_id')) {
                $table->dropColumn('model_id')->nullable();
            }

            if (Schema::hasColumn('cms_seo', 'model')) {
                $table->dropColumn('model')->nullable();
            }

            # add
            if (!Schema::hasColumn('cms_seo', 'title')) {
                $table->string('title')->nullable();
            }

            if (!Schema::hasColumn('cms_seo', 'description')) {
                $table->longText('description')->nullable();
            }

            if (!Schema::hasColumn('cms_seo', 'keywords')) {
                $table->longText('keywords')->nullable();
            }

            if (!Schema::hasColumn('cms_seo', 'author')) {
                $table->string('author')->nullable();
            }

            if (!Schema::hasColumn('cms_seo', 'language')) {
                $table->string('language')->nullable();
            }

            if (!Schema::hasColumn('cms_seo', 'page')) {
                $table->string('page')->nullable();
            }

            if (!Schema::hasColumn('cms_seo', 'page_id')) {
                $table->integer('page_id')->nullable();
            }

        });
    }
}
