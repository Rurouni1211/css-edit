<?php

namespace Database\Seeders;

use App\Models\Artisan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArtisanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $encrypted_password = (app()->environment('local'))
            ? bcrypt('xxxxxxxx')
            : bcrypt('jqNtVy4L0oOrgdeHAAWc2yxOyieViqHP');

        for($i = 0; $i < 10; $i++) {

            $number = $i + 1;
            $email = 'artisan' . $number . '@example.com';

            $artisan = new Artisan();
            $artisan->name = '職人' . $number;
            $artisan->email = $email;
            $artisan->password = $encrypted_password;
            $artisan->save();

        }
    }
}
