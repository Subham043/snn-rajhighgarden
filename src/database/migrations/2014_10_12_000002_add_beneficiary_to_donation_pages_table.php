<?php

use App\Enums\GenderEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('donation_pages', function (Blueprint $table) {
            $table->string('beneficiary_bank_name')->nullable();
            $table->string('beneficiary_bank_account_number')->nullable();
            $table->string('beneficiary_bank_ifsc_code')->nullable();
            $table->string('beneficiary_upi_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('donation_pages', function (Blueprint $table) {});
    }
};
