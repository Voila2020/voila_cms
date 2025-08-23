<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAIContentAPIsLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up(): void
    {
        Schema::create('ai_content_apis_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ai_api_log_id')->nullable();
            $table->string('ai_api_key', 255)->nullable();
            $table->integer('usage_prompt_tokens')->nullable();
            $table->integer('usage_completion_tokens')->nullable();
            $table->integer('usage_total_tokens')->nullable();
            $table->timestamp('created_at')->useCurrent()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_content_apis_logs');
    }
    
}
