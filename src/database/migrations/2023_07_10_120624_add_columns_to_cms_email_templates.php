<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToCmsEmailTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cms_email_templates', function (Blueprint $table) {
            $table->longtext('content_ar')->nullable();
            $table->longText('template_ar')->nullable();
            $table->longText('html_ar')->nullable();
            $table->longText('css_ar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cms_email_templates', function (Blueprint $table) {
            
        });
    }
}
