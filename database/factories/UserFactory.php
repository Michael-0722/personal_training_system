<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $baseUsername = Str::lower(Str::slug(fake()->userName(), ''));

        return [
            'username' => $baseUsername . fake()->numberBetween(1000, 999999),
            'full_name' => fake()->name(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => fake()->randomElement(['client', 'trainer']),
            'account_status' => 'active',
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this;
    }
}
