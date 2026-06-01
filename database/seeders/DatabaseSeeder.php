<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
	public function run(): void
	{
		User::factory()->create([
			'name'     => 'Admin',
			'email'    => 'admin@example.com',
            'password' => 'password',
			'is_admin' => true,
		]);

        User::factory()->create([
            'name'     => 'User',
            'email'    => 'user@example.com',
            'password' => 'password',
            'is_admin' => false,
        ]);

		$this->call([
			CategorySeeder::class,
			ProductSeeder::class,
		]);
	}
}
