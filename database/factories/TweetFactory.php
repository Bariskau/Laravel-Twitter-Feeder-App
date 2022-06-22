<?php

namespace Database\Factories;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TweetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tweet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            Tweet::USER_ID    => User::factory(),
            Tweet::TWEET_ID   => $this->faker->numerify('###############'),
            Tweet::TWEET      => $this->faker->realText(280),
            Tweet::TWEET_DATE => $this->faker->date,
        ];
    }
}
