<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->decimal('base_amount', 15, 2)->default(0)->after('amount');
            $table->unsignedSmallInteger('unique_code')->default(0)->after('base_amount');
            $table->string('payment_bank')->nullable()->after('metadata');
            $table->string('payment_account_number')->nullable()->after('payment_bank');
            $table->string('payment_account_name')->nullable()->after('payment_account_number');
            $table->timestamp('verified_at')->nullable()->after('payment_account_name');
            $table->foreignId('verified_by')->nullable()->after('verified_at')->constrained('users')->nullOnDelete();
            $table->text('admin_note')->nullable()->after('verified_by');
        });

        DB::table('donations')->update([
            'base_amount' => DB::raw('amount'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn([
                'base_amount',
                'unique_code',
                'payment_bank',
                'payment_account_number',
                'payment_account_name',
                'verified_at',
                'verified_by',
                'admin_note',
            ]);
        });
    }
};
