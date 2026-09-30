<?php

namespace Database\Seeders;

use App\Models\Measurements;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MeasurementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $measurements = DB::connection('dohis_lims')->table('lims_measurements')->get();

        $measurements->each(function($measurement){
            Measurements::create([
                'name' => $measurement->name
            ]);
        });
    }
}
