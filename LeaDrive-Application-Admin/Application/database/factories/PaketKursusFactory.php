<?php

namespace Database\Factories;

use App\Models\PaketKursus;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaketKursusFactory extends Factory
{
    protected $model = PaketKursus::class;

    public function definition(): array
    {
        return [
            'nama_paket' => $this->faker->randomElement(['Paket Dasar', 'Paket Mahir', 'Paket Kilat', 'Paket VIP', 'Paket Weekend']),
            'harga' => $this->faker->numberBetween(500000, 2000000),
            'durasi_jam' => $this->faker->numberBetween(10, 40),
            'deskripsi' => $this->faker->sentence(),
            // 'id_request' => null, // Assuming not strictly required for dummy data or nullable
        ];
    }
}
