<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dateTime('ends_at')->nullable()->change();
        });
    }

    public function down(): void
    {
        DB::table('listings')->whereNull('ends_at')->update(['ends_at' => now()]);

        Schema::table('listings', function (Blueprint $table) {
            $table->dateTime('ends_at')->nullable(false)->change();
        });
    }
};
