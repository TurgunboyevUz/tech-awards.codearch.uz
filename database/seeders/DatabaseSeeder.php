<?php
namespace Database\Seeders;

use App\Models\Currency;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name'     => 'Tech Award Admin',
            'email'    => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        Currency::insert([
            [
                'name'       => 'Tilla',
                'code'       => 'XAU',
                'buy_price'  => 15500,
                'sell_price' => 15000,
            ],
        ]);
    }
}
