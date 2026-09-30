<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertyUserHistory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Fetch properties from the remote connection
        // (Note: Fixed typo 'Acticve' -> 'Active' if needed, but preserved your query string)
        $properties = DB::connection('dohis_lims')->table('lims_properties')->where('status', 'Active')->get();
        
        // 2. Fetch measurements keyed by ID for O(1) lookup
        $measurements = DB::connection('dohis_lims')->table('lims_measurements')->get()->keyBy('id');

        // 3. Fetch categories keyed by ID for O(1) lookup
        $categories = DB::connection('dohis_lims')->table('categories')->get()->keyBy('id');

        

        // 3. Map over properties and insert into the local database
        $properties->each(function ($prop) use ($measurements, $categories) {
            // Find measurement unit name (safely falls back to raw value if ID doesn't exist)
            $measurementUnit = $measurements->get($prop->measurement_unit)->name ?? 'Undefined';

            // Find category name (safely falls back to raw value if ID doesn't exist)
            $categoryLabel = $categories->get($prop->main_category_id)->name ?? 'Undefined';

            $user = DB::connection('dohis_lims')->table('property_user')->where('property_id',$prop->id)->first();
            $userHistory = DB::connection('dohis_lims')->table('property_user_history')->where('property_id',$prop->id)->get();

            $propertyCreated = Property::create([
                'property_no'      => $prop->property_no,
                'measurement_unit' => $measurementUnit,
                'particulars'      => $prop->particulars,
                'unit_cost'        => $prop->unit_cost,
                'status'           => $prop->status,
                'remarks'          => $prop->remarks,
                'category'         => $categoryLabel,
                'mr_to'            => $user->user_id,
                'acquisition_date' => $user->issuance_date ?? null,
                'specs'            => null
            ]);

            foreach($userHistory as $history){
                PropertyUserHistory::create([
                    'property_id'    => $propertyCreated->id,
                    'mr_to'          => $history->user_id,
                    'issuance_date'  => $history->acquisition_date,
                    'return_date'    => $history->return_date,
                ]);
            }

            $this->command->info('Property Created.');

        });
    }
}