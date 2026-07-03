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
            $table->string('payment_status')->default('pending')->after('payment_method');
        });

        DB::table('orders')->update([
            'payment_status' => DB::raw("
                CASE
                    WHEN status = 'pending' THEN 'pending'
                    ELSE 'paid'
                END
            "),
        ]);

        DB::table('orders')->update([
            'status' => DB::raw("
                CASE
                    WHEN status = 'delivered' THEN 'delivered'
                    WHEN status = 'cancelled' THEN 'on_hold'
                    ELSE 'processing'
                END
            "),
        ]);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropColumn('payment_status');
        });
    }
};
