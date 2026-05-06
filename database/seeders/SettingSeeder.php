<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $setting = Setting::first() ?? new Setting();

        $setting->site_name = $setting->site_name ?? 'SmartLookBD';
        
        // Force set SMS Defaults
        $setting->sms_api_url = 'http://bulksmsbd.net/api/smsapi?api_key=[KEY]&type=text&number=[TO]&senderid=[SENDER]&message=[MESSAGE]';
        $setting->is_sms_active = true;
        
        $setting->save();
        
        echo "Settings seeded successfully with forced SMS URL.\n";
    }
}
