<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $national = Region::firstOrCreate(
            ['code' => 'ID'],
            [
                'name' => 'Indonesia (Nasional)',
                'level' => 'national',
                'parent_id' => null,
                'is_active' => true,
            ]
        );

        $provinces = [
            ['code' => 'ID-JK', 'name' => 'DKI Jakarta', 'level' => 'province'],
            ['code' => 'ID-JB', 'name' => 'Jawa Barat', 'level' => 'province'],
            ['code' => 'ID-JT', 'name' => 'Jawa Tengah', 'level' => 'province'],
            ['code' => 'ID-YO', 'name' => 'D.I. Yogyakarta', 'level' => 'province'],
            ['code' => 'ID-JI', 'name' => 'Jawa Timur', 'level' => 'province'],
            ['code' => 'ID-BA', 'name' => 'Bali', 'level' => 'province'],
            ['code' => 'ID-SU', 'name' => 'Sumatera Utara', 'level' => 'province'],
            ['code' => 'ID-SB', 'name' => 'Sumatera Barat', 'level' => 'province'],
            ['code' => 'ID-SS', 'name' => 'Sumatera Selatan', 'level' => 'province'],
            ['code' => 'ID-SN', 'name' => 'Sulawesi Selatan', 'level' => 'province'],
            ['code' => 'ID-KB', 'name' => 'Kalimantan Barat', 'level' => 'province'],
            ['code' => 'ID-KT', 'name' => 'Kalimantan Timur', 'level' => 'province'],
            ['code' => 'ID-NT', 'name' => 'Nusa Tenggara Timur', 'level' => 'province'],
        ];

        foreach ($provinces as $prov) {
            Region::firstOrCreate(
                ['code' => $prov['code']],
                [
                    'name' => $prov['name'],
                    'level' => $prov['level'],
                    'parent_id' => $national->id,
                    'is_active' => true,
                ]
            );
        }
    }
}
