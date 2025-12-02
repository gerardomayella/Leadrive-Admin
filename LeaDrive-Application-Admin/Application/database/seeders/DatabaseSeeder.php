<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\PaketKursus;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin if not exists
        DB::table('admin')->updateOrInsert(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin LeadDrive',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 2. Create Users (Pemilik Kursus)
        $users = User::factory(10)->create([
            'role' => 'pemilik_kursus',
            'status' => 'active',
        ]);

        // 3. Create Paket Kursus (Dummy packages)
        $pakets = PaketKursus::factory(5)->create();

        // 4. Create Transactions
        // Create some transactions for existing users
        foreach ($users as $user) {
            // Each user has 1-3 transactions
            Transaction::factory(rand(1, 3))->create([
                'user_id' => $user->id,
                'paket_kursus_id' => $pakets->random()->id_paket,
                'amount' => $pakets->random()->harga, // Simplified logic
            ]);
        }

        // Create some pending transactions
        Transaction::factory(5)->create([
            'status' => 'pending',
            'user_id' => $users->random()->id,
            'paket_kursus_id' => $pakets->random()->id_paket,
        ]);
        
        // Create some recent success transactions
        Transaction::factory(5)->create([
            'status' => 'success',
            'user_id' => $users->random()->id,
            'paket_kursus_id' => $pakets->random()->id_paket,
            'created_at' => now()->subDays(rand(0, 2)),
        ]);
    }
}
