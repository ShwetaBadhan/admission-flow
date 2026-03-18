<?php

namespace Database\Seeders;
use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    public function run()
    {
        $data = require database_path('data/india_states_cities.php');

        foreach ($data as $stateCode => $state) {
            State::firstOrCreate(
                ['code' => $stateCode],
                ['name' => $state['name']]
            );
        }
    }
}

