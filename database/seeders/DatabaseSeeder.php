<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
public function run(): void
{
    $this->call([
        RoleSeeder::class,
        DepartmentSeeder::class,
        OrderStatusSeeder::class,
        UserSeeder::class,
        OrderSeeder::class,
    ]);

    // Crear datos adicionales usando factories
    \App\Models\Client::factory(5)->create();
    \App\Models\Product::factory(10)->create();
    \App\Models\Order::factory(15)->create();
}

}