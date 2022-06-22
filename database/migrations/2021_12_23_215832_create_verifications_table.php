<?php

use App\Models\Verification;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verifications', function (Blueprint $table) {
            $table->id();
            $table->string(Verification::TYPE);
            $table->boolean(Verification::VALID)->default(true);
            $table->string(Verification::VALIDITY);
            $table->string(Verification::TOKEN);
            $table->foreignId(Verification::USER_ID)
                ->constrained()
                ->onDelete('cascade');

            $table->index([Verification::USER_ID, Verification::VALIDITY]);

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
        Schema::dropIfExists('verifications');
    }
}
