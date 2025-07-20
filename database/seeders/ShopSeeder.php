<?php

namespace Database\Seeders;

use App\Models\Shop;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $encrypted_password = (app()->environment('local'))
            ? bcrypt('xxxxxxxx')
            : bcrypt('jqNtVy4L0oOrgdeHAAWc2yxOyieViqHP');

        for($i = 0; $i < 15; $i++) {

            $number = $i + 1;
            $email = 'shop' . $number . '@example.com';

            $shop = new Shop();
            $shop->name = '店舗' . $number;
            $shop->email = $email;
            $shop->password = $encrypted_password;
            $shop->save();

        }
    }
}
