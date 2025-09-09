<?php

namespace Database\Seeders;

use App\Models\SmsConfiguration;
use Illuminate\Database\Seeder;

class SmsConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create initial configuration from environment variables if it doesn't exist
        SmsConfiguration::firstOrCreate(
            ['id' => 1],
            [
                'active_provider' => 'smsq',
                'smsq_api_key' => config('services.smsq.api_key'),
                'smsq_client_id' => config('services.smsq.client_id'),
                'smsq_sender_id' => config('services.smsq.sender_id'),
                'bulksmsbd_api_key' => config('services.bulksmsbd.api_key'),
                'bulksmsbd_sender_id' => config('services.bulksmsbd.sender_id'),
            ]
        );
    }
}