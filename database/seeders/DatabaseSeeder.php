<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'rizzpathan2@gmail.com'],
            [
                'name' => 'Rizwan Pathan',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'plan' => 'elite',
            ]
        );
    }
}
