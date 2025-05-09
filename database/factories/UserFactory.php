<?php

namespace Database\Factories;

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
            'rec1' => $faker->tipeHewan(),
            'rec2' => $faker->jenisHewan($tipeHewan),
            'rec3' => $faker->genderHewan(),
            'rec4' => $faker->kelompokUsia($tipeHewan),
            'rec5' => $faker->jumlahWarna(),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
    ];
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
