<?php

use App\Enums\RoleEnum;
use App\Enums\UserStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {

            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->integer('userType')->default(RoleEnum::USER->label());
            $table->integer('status')->default(UserStatusEnum::VERIFICATION_PENDING->label());
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('otp');
            $table->rememberToken();
            $table->timestamps();
            $table->index(['email', 'phone', 'otp', 'id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
