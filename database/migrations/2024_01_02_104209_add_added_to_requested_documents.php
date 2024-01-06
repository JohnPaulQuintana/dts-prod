<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('requested_documents', function (Blueprint $table) {
            $table->string('generate_pr_by')->nullable();
            $table->string('generate_po_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requested_documents', function (Blueprint $table) {
            $table->dropColumn('generate_pr_by');
            $table->dropColumn('generate_po_by');
        });
    }
};
