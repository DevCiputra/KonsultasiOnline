<?php

namespace Database\Seeders;

use App\Models\CategoryPolyclinic;
use Illuminate\Database\Seeder;

class CategoryPolyclinicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Data poliklinik
        $polyclinics = [
            ['category_polyclinic' => 'Umum'],
            ['category_polyclinic' => 'Anak'],
            ['category_polyclinic' => 'Gigi'],
            ['category_polyclinic' => 'Jantung'],
            ['category_polyclinic' => 'Saraf'],
            ['category_polyclinic' => 'Mata'],
            ['category_polyclinic' => 'THT'],
            ['category_polyclinic' => 'Kulit dan Kelamin'],
            ['category_polyclinic' => 'Orthopedi'],
            ['category_polyclinic' => 'Andrologi'],
        ];

        // Insert data ke tabel doctor_polyclinics
        foreach ($polyclinics as $polyclinic) {
            CategoryPolyclinic::create($polyclinic);
        }
    }
}
