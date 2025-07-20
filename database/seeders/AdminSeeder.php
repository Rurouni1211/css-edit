<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
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
            $email = 'admin' . $number . '@example.com';

            $admin = new Admin();
            $admin->name = '管理者' . $number;
            $admin->email = $email;
            $admin->password = $encrypted_password;
            $admin->save();

        }
    }
}
