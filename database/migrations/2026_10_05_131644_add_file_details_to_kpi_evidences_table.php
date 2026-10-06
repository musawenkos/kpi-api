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
        Schema::table('kpi_evidence', function (Blueprint $table) {
            $table->string('mime_type', 100)->after('path');
            $table->unsignedBigInteger('size_bytes')->after('mime_type');
            $table->char('sha256', 64)->after('size_bytes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kpi_evidence', function (Blueprint $table) {
            $table->dropColumn(['mime_type','size_bytes','sha256']);
        });
    }
};
