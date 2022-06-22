<?php

use App\Enums\TweetStatus;
use App\Models\Tweet;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTweetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tweets', function (Blueprint $table) {
            $table->id();
            $table->uuid(Tweet::UUID);
            $table->text(Tweet::TWEET);
            $table->timestamp(Tweet::TWEET_DATE);
            $table->bigInteger(Tweet::TWEET_ID);
            $table->string(Tweet::STATUS)->default(TweetStatus::passive);
            $table->foreignId(Tweet::USER_ID)
                ->constrained()
                ->onDelete('cascade');

            $table->index([Tweet::UUID, Tweet::TWEET, Tweet::TWEET_DATE, Tweet::STATUS, Tweet::TWEET_ID]);
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
        Schema::dropIfExists('tweets');
    }
}
