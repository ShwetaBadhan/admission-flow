<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LeadSource;

class LeadSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $sources = [
            ['name' => 'Facebook'],
            ['name' => 'Instagram'],
            ['name' => 'Website'],
            ['name' => 'Referral'],
            ['name' => 'WhatsApp'],
            ['name' => 'Google Ads'],
            ['name' => 'Walk-in'],
            ['name' => 'Consultant'],
            ['name' => 'Email Campaign'],
            ['name' => 'Other'],
        ];

        foreach ($sources as $source) {
            LeadSource::firstOrCreate(
                ['name' => $source['name']],
                ['status' => 1]
            );
        }
    }
}
