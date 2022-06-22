<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    const twitterAddresses = [
        'ProfDemirtas',
        'elonmusk',
        'SpaceX',
        'NASA'
    ];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            User::NAME            => $this->faker->name,
            User::EMAIL           => $this->faker->unique()->safeEmail,
            User::PASSWORD        => $this->faker->randomNumber(8),
            User::PHONE_NUMBER    => $this->faker->numerify('##########'),
            User::TWITTER_ADDRESS => self::twitterAddresses[array_rand(self::twitterAddresses)],
        ];
    }
}
