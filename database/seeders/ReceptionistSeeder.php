<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ReceptionistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Receptionist One',
            'email' => 'receptionist@example.com',
            'password' => Hash::make('receptionist'), // Hash the password here
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            // Add any other necessary fields
        ]);
    }
}
