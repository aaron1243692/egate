<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call([
        //     PermissionRoleSeeder::class,
        //     EgateLogSeeder::class,
        // ]);

        $user = User::query()->updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'username' => 'admin',
                'password' => bcrypt('admin1928'),
            ]
        );
        $user->assignRole('admin');
    }
}
