<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use App\Models\PaketKursus;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'paket_kursus_id' => PaketKursus::factory(),
            'amount' => $this->faker->numberBetween(500000, 2000000),
            'status' => $this->faker->randomElement(['pending', 'success', 'failed', 'cancelled']),
            'payment_method' => $this->faker->randomElement(['bank_transfer', 'ewallet', 'credit_card']),
            'payment_proof' => null,
            'transaction_date' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'created_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'updated_at' => function (array $attributes) {
                return $attributes['created_at'];
            },
        ];
    }
}
