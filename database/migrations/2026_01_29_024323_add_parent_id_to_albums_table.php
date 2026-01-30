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
        Schema::table('albums', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('artist_id')->constrained('albums')->cascadeOnDelete();
            $table->integer('order')->default(0)->nullable()->after('artwork_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('albums', function (Blueprint $table) {
            $table->dropColumn(['parent_id', 'order']);
        });
    }
};
