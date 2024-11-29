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
        if ($this->checkStatusExpired() === false) {
            DB::statement("ALTER TABLE bookings MODIFY payment_status ENUM('pending', 'paid', 'cancel', 'refund', 'expired') DEFAULT 'pending'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ($this->checkStatusExpired() === false) {
            DB::statement("ALTER TABLE bookings MODIFY payment_status ENUM('pending', 'paid', 'cancel', 'refund') DEFAULT 'pending'");
        }
    }

    public function checkStatusExpired(): bool {
        $columns = DB::select("SHOW COLUMNS FROM bookings");
        $column = collect($columns)->firstWhere('Field', 'payment_status');
        $enumValues = str_replace(['enum(', ')', "'"], '', $column->Type);
        $values = explode(',', $enumValues);

        return in_array('expired', $values);
    }
};
