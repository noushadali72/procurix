<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AccountCategorySeeder::class,
            AccountSeeder::class,
            CategorySeeder::class,
            UnitCategorySeeder::class,
            UnitSeeder::class,
            ProductSeeder::class,
            RawMaterialSeeder::class,
            VendorSeeder::class,
            PaymentTermSeeder::class,
        ]);
    }
}