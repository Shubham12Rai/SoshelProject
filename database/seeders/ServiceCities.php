<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceCities extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('service_cities')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('service_cities')->insert([
            [
                'city_pointer_name' => 'Hopkins',
                'city_name' => 'Cleveland Ohio',
                'latitude' => 41.418155,
                'longitude' => -81.842722,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'city_pointer_name' => 'Kamm Corners',
                'city_name' => 'Cleveland Ohio',
                'latitude' => 41.445905,
                'longitude' => -81.811185,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'city_pointer_name' => 'Bellaire-Puritas',
                'city_name' => 'Cleveland Ohio',
                'latitude' => 41.432592,
                'longitude' => -81.782768,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'city_pointer_name' => 'Jefferson',
                'city_name' => 'Cleveland Ohio',
                'latitude' => 41.463170,
                'longitude' => -81.774908,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'city_pointer_name' => 'Cudell',
                'city_name' => 'Cleveland Ohio',
                'latitude' => 41.470743,
                'longitude' => -81.749198,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'city_pointer_name' => 'Stockyards',
                'city_name' => 'Cleveland Ohio',
                'latitude' => 41.452385,
                'longitude' => -81.719153,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'city_pointer_name' => 'Mt Pleasant',
                'city_name' => 'Cleveland Ohio',
                'latitude' => 41.456576,
                'longitude' => -81.597802,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'city_pointer_name' => 'Asiatown',
                'city_name' => 'Cleveland Ohio',
                'latitude' => 41.508746,
                'longitude' => -81.659128,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'city_pointer_name' => 'South - Collinwood',
                'city_name' => 'Cleveland Ohio',
                'latitude' => 41.553339,
                'longitude' => -81.578594,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'city_pointer_name' => 'Glenville',
                'city_name' => 'Cleveland Ohio',
                'latitude' => 41.418155,
                'longitude' => -81.624282,
                'created_at' => now(),
                'updated_at' => now()
            ],

        ]);
    }
}
