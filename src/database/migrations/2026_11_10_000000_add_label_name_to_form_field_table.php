<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * AdminFormsController and CmsFormController read form_field.label_name, but no migration created it,
     * so the forms module failed on new projects. Existing projects added the column by hand: skip them.
     */
    public function up(): void
    {
        if (Schema::hasTable('form_field') && ! Schema::hasColumn('form_field', 'label_name')) {
            Schema::table('form_field', function (Blueprint $table) {
                $table->string('label_name')->nullable();
            });
        }
    }

    /**
     * Left empty on purpose: the column may predate this migration, and dropping it would lose data.
     */
    public function down(): void
    {
    }
};
