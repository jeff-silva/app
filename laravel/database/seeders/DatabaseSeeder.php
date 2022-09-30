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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $user = \App\Models\Users::firstOrNew(['id' => 1], [
            'name' => 'Root User',
            'email' => 'root@grr.la',
            'password' => \Hash::make('321321'),
        ]);

        $user->save();
    }
}
