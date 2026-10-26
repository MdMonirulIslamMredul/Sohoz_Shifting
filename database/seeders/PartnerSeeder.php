<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $partners = [
            [
                'name' => 'SMA Solar Technology',
                'image' => 'partners/sma-solar.svg',
                'website_url' => 'https://www.sma.de/en',
                'description' => 'Global leader in solar inverter manufacturing, system technology, and commercial energy management solutions.',
                'sort_order' => 1,
                'status' => true,
            ],
            [
                'name' => 'Tesla Energy',
                'image' => 'partners/tesla-energy.svg',
                'website_url' => 'https://www.tesla.com/energy',
                'description' => 'Pioneering clean energy storage systems, Powerwall batteries, and utility-scale Megapack grid infrastructure.',
                'sort_order' => 2,
                'status' => true,
            ],
            [
                'name' => 'Siemens Energy',
                'image' => 'partners/siemens-energy.svg',
                'website_url' => 'https://www.siemens-energy.com',
                'description' => 'International technology giant driving power transmission, grid stability, and industrial green transition.',
                'sort_order' => 3,
                'status' => true,
            ],
            [
                'name' => 'SunPower Systems',
                'image' => 'partners/sunpower.svg',
                'website_url' => 'https://us.sunpower.com',
                'description' => 'Industry-leading high-efficiency Maxeon monocrystalline photovoltaic modules and rooftop solar tech.',
                'sort_order' => 4,
                'status' => true,
            ],
            [
                'name' => 'Huawei Smart PV',
                'image' => 'partners/huawei-solar.svg',
                'website_url' => 'https://solar.huawei.com',
                'description' => 'Smart string inverters, AI-driven photovoltaic diagnostics, and intelligent renewable energy management.',
                'sort_order' => 5,
                'status' => true,
            ],
            [
                'name' => 'Enphase Energy',
                'image' => 'partners/enphase-energy.svg',
                'website_url' => 'https://enphase.com',
                'description' => 'Microinverter technology and integrated home solar battery storage with module-level performance optimization.',
                'sort_order' => 6,
                'status' => true,
            ],
        ];

        foreach ($partners as $partnerData) {
            Partner::updateOrCreate(
                ['name' => $partnerData['name']],
                $partnerData
            );
        }
    }
}
