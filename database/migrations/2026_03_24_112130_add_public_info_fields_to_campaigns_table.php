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
        Schema::table('campaigns', function (Blueprint $table) {
            $table->string('location')->nullable()->after('cta_label');
            $table->string('focus_area')->nullable()->after('location');
            $table->string('distribution_plan')->nullable()->after('focus_area');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn([
                'location',
                'focus_area',
                'distribution_plan',
            ]);
        });
    }
};
