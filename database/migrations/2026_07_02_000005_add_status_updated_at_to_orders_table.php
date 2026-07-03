<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->timestamp('status_updated_at')->nullable()->after('status');
        });

        DB::table('orders')->update([
            'status_updated_at' => DB::raw('COALESCE(updated_at, created_at)'),
        ]);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropColumn('status_updated_at');
        });
    }
};
