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
         Schema::table('pickups', function (Blueprint $table) {
        $table->string('cancel_reason')->nullable();
        $table->foreignId('cancelled_by')->nullable()->constrained('users');
        $table->timestamp('cancelled_at')->nullable();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pickups', function (Blueprint $table) {
            //
        });
    }
};
