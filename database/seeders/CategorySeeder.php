<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = DB::connection('dohis_lims')->table('categories')->get();

        $categories->each(function($cat){
            Category::create([
                'name' => $cat->name
            ]);
        });
    }
}
