<?php

use App\Models\User;
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
            $table->uuid(User::UUID);
            $table->string(User::NAME);
            $table->string(User::PASSWORD);
            $table->string(User::EMAIL)->unique();
            $table->string(User::TWITTER_ADDRESS);
            $table->string(User::SESSION_ID)->nullable();
            $table->bigInteger(User::PHONE_NUMBER);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('sms_verified_at')->nullable();

            $table->index([User::UUID, User::TWITTER_ADDRESS, User::EMAIL, User::PHONE_NUMBER]);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
