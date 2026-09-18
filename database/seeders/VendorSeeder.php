<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = [
            [
                'name' => 'Techno Supplies',
                'company_name' => 'Techno Supplies Pvt Ltd',
                'contact_person' => 'Ahmed Khan',
                'email' => 'ahmed@technosupplies.test',
                'phone' => '03001234567',
                'ntn' => '1234567-8',
                'address' => 'SITE Area, Karachi',
                'is_active' => true,
            ],
            [
                'name' => 'Prime Traders',
                'company_name' => 'Prime Traders & Co.',
                'contact_person' => 'Usman Ali',
                'email' => 'usman@primetraders.test',
                'phone' => '03111234567',
                'ntn' => '2345678-9',
                'address' => 'Saddar, Karachi',
                'is_active' => true,
            ],
            [
                'name' => 'Metro Industrial',
                'company_name' => 'Metro Industrial Supplies',
                'contact_person' => 'Bilal Ahmed',
                'email' => 'bilal@metroindustrial.test',
                'phone' => '03221234567',
                'ntn' => '3456789-0',
                'address' => 'Korangi Industrial Area, Karachi',
                'is_active' => true,
            ],
            [
                'name' => 'Pak Packaging',
                'company_name' => 'Pak Packaging Solutions',
                'contact_person' => 'Hassan Raza',
                'email' => 'hassan@pakpackaging.test',
                'phone' => '03331234567',
                'ntn' => '4567890-1',
                'address' => 'North Karachi, Karachi',
                'is_active' => true,
            ],
            [
                'name' => 'Global Hardware',
                'company_name' => 'Global Hardware & Tools',
                'contact_person' => 'Fahad Malik',
                'email' => 'fahad@globalhardware.test',
                'phone' => '03441234567',
                'ntn' => '5678901-2',
                'address' => 'Gulshan-e-Iqbal, Karachi',
                'is_active' => true,
            ],
        ];

        foreach ($vendors as $vendor) {
            Vendor::create($vendor);
        }
    }
}