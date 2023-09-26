<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
         \App\Models\User::factory()->create([
             'name' => 'Test User',
             'email' => 'test@example.com',
             'password'=> bcrypt('123123'),
         ]);
        /*
        $faker = Faker::create();


        $categoryIds = DB::table('categories')->pluck('id')->toArray();
        $brandIds = DB::table('brands')->pluck('id')->toArray();
        $vehicleTypeIds = DB::table('vehicle_types')->pluck('id')->toArray();

        for ($i = 0; $i < 50; $i++) {
            DB::table('products')->insert([
                'name' => $faker->sentence(3), // Rastgele bir cümle oluştur
                'description' => $faker->paragraph, // Rastgele bir paragraf oluştur
                'image' => Storage::url('public/images/example.png'),
                'image_url' => asset('storage/images/example.png'),
                'motor_model' => $faker->sentence(3), // Rastgele bir cümle oluştur
                'oem_code' => $faker->unique()->bothify('???###'),
                'product_code' => $faker->unique()->bothify('???###'),
                'stock_entry_date' => now(),
                'category_id' => $faker->randomElement($categoryIds),
                'brand_id' => $faker->randomElement($brandIds),
                'vehicle_type_id' => $faker->randomElement($vehicleTypeIds),
                'created_at' => now(), // Eklenen satır
                'updated_at' => now(), // Eklenen satır
            ]);
        }
*/
        
         // DB::table('products')->truncate();

    }
}
