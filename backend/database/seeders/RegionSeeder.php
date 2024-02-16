<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Distributor;
use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        //Region::factory(4)->has(City::factory(1)->has(Distributor::factory(23)))->create();
        Region::factory(4)->create();
    }
}
