<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\City;
use App\Models\Distributor;
use App\Models\Region;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*$this->call([
            RegionSeeder::class,
            CitySeeder::class,
            DistributorSeeder::class,
        ]); */
        Region::factory(4)->has(City::factory(1)->has(Distributor::factory(23)))->create();
    }
}
