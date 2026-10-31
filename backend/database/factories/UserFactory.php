<?php

namespace Database\Factories;

<<<<<<< HEAD
=======
use App\Models\User;
>>>>>>> 6b61ff51a6ef315dbc9e8dc7c1fb0ae7e86644ff
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

<<<<<<< HEAD
class UserFactory extends Factory
{
    protected static ?string $password;

=======
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
>>>>>>> 6b61ff51a6ef315dbc9e8dc7c1fb0ae7e86644ff
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
<<<<<<< HEAD
            'nivel_educativo' => null,
            'curso' => null,
        ];
    }

=======
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
>>>>>>> 6b61ff51a6ef315dbc9e8dc7c1fb0ae7e86644ff
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
