<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        
        User::truncate();
        Profile::truncate();
        
        
        $UserQuantity = 5;
        $ProfileQuantity = 5;
        
        User::factory($UserQuantity)->create();

        Profile::factory($ProfileQuantity)->create();
            
    }
}
