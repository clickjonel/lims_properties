<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Michelle Martin-Santiago',
                'username' => 'michele'

            ],

        ];

        foreach($users as $user){
            User::create([
                'name' => $user['name'],
                'username' => $user['username'],
                'password' => bcrypt('12345'),
            ]);
        }



    }
}
