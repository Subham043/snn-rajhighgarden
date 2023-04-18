<?php

use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donations', function (Blueprint $table) {

            $table->id();
            $table->string('name');
            $table->string('email')->index();
            $table->string('phone')->nullable()->index();
            $table->text('message')->nullable();
            $table->bigInteger('amount');
            $table->text('order_id')->nullable()->index();
            $table->text('receipt')->nullable();
            $table->text('payment_id')->nullable()->index();
            $table->integer('status')->default(PaymentStatusEnum::PENDING->label());
            $table->bigInteger('donation_page_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('donations');
    }
}
