<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ContactStage;

class ContactStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $stages = [
            ['name' => 'Contacted'],
            ['name' => 'Not Contacted'],
            ['name' => 'Closed'],
            ['name' => 'Qualified'],
            ['name' => 'Negotiation'],
            ['name' => 'Lead'],
            ['name' => '	New Lead'],
        
        ];

        foreach ($stages as $stage) {
            ContactStage::firstOrCreate(
                ['name' => $stage['name']],
                ['status' => 1]
            );
        }
    }
}
