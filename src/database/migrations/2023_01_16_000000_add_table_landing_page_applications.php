

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableLandingPageApplications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('landing_page_applications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('landing_page_id');
            $table->string('ip')->nullable();
            $table->string('ip_details')->nullable();
            $table->string('refer_link')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('landing_page_applications');
    }
}
