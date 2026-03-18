<?php

namespace Database\Seeders;
use App\Models\City;
use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CitySeeder extends Seeder
{
    public function run()
    {
        $data = require database_path('data/india_states_cities.php');

        foreach ($data as $stateCode => $stateData) {
            $state = State::where('code', $stateCode)->first();
            if (!$state) continue;

            foreach ($stateData['cities'] as $cityName) {

                $code = strtoupper(
                    substr(
                        preg_replace('/[^A-Za-z]/', '', $cityName),
                        0,
                        3
                    )
                );

                City::firstOrCreate(
                    [
                        'state_id' => $state->id,
                        'name' => $cityName,
                    ],
                    [
                        'code' => $code,
                    ]
                );
            }
        }
    }
}
