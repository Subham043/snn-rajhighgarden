<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donation_pages', function (Blueprint $table) {

            $table->id();
            $table->text('donation_title');
            $table->text('image');
            $table->text('image_alt')->nullable();
            $table->text('image_title')->nullable();
            $table->string('slug')->unique();
            $table->bigInteger('funds_required')->nullable();
            $table->timestamp('fund_required_within', 0)->nullable();
            $table->string('campaigner_name')->nullable();
            $table->string('campaigner_funds_collected')->nullable();
            $table->string('beneficiary_name')->nullable();
            $table->string('beneficiary_relationship_with_campaigner')->nullable();
            $table->string('beneficiary_funds_collected')->nullable();
            $table->text('donation_detail');
            $table->text('terms_condition')->nullable();
            $table->bigInteger('user_id');
            $table->timestamps();
            $table->index(['id', 'slug', 'user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('donation_pages');
    }
}
