<?php

namespace Database\Seeders;

use App\Models\Customer;
use Database\Factories\CustomerFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
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
            $email = 'customer' . $number . '@example.com';

            $customer = new Customer();
            $customer->name = '顧客' . $number;
            $customer->email = $email;
            $customer->password = $encrypted_password;
            $customer->email_verified_at = now();
            $customer->save();

        }
    }
}
