<?php

namespace Database\Factories;

use App\Models\Reference;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Providers\CustomFakerProvider;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
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
        $faker = fake();
        $faker->addProvider(new CustomFakerProvider($faker));

        $tipeHewan = $faker->tipeHewan();

        return [
            'user_id' => strtoupper(Str::random(2)) . str_pad($faker->randomNumber(3, false), 3, '0', STR_PAD_LEFT),
            'username' => $faker->unique()->username(),
            'email' => $faker->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'phone' => $faker->phoneNumber(),
            'alamat' => $faker->alamat(),
            'kelurahan' => $faker->kelurahan(),
            'kecamatan' => $faker->kecamatan(),
            'kota' => 'Jakarta',
            'provinsi' => 'Jawa Barat',
            'description' => 'Hello, I love animals and I am looking for a new friend that needs a forever home.',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
    ];
    }

    public function withReference(): static
    {
        return $this->afterCreating(function ($user) {
            $faker = fake();
            $faker->addProvider(new CustomFakerProvider($faker));

            $tipeHewan = $faker->tipeHewan();

            // Create a reference for the user
            $user->reference()->create([
                'animal_type' => $faker->tipeHewan(),
                'breed' => $faker->jenisHewan($tipeHewan),
                'animal_gender' => $faker->genderHewan(),
                'age_group' => $faker->kelompokUsia($tipeHewan),
                'color_count' => $faker->jumlahWarna(),
            ]);
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
