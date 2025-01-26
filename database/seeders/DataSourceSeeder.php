<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataSource;

class DataSourceSeeder extends Seeder
{
    public function run()
    {
        $dataSources = [
            ['name' => 'NewsAPI'],
            ['name' => 'NewYorkTimes'],
            ['name' => 'Guardian'],
        ];

        foreach ($dataSources as $dataSource) {
            DataSource::create($dataSource);
        }
    }
}
