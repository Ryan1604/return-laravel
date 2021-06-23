<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (Schema::hasTable('roles')) {
            if (DB::table('roles')->count() > 0) {
                DB::table('roles')->truncate();
            }

            DB::table('roles')->insert([
                [
                    'name' => 'Admin',
                ],
                [
                    'name' => 'Manager',
                ],
                [
                    'name' => 'User',
                ],
            ]);
        }

        if (Schema::hasTable('users')) {
            if (DB::table('users')->count() > 0) {
                DB::table('users')->truncate();
            }

            DB::table('users')->insert([
                [
                    'name' => 'Admin',
                    'email' => 'administrator@mail.com',
                    'password' => bcrypt('123'),
                    'profile_url' => 'admin.jpg',
                    'role_id' => 1,
                    'disabled' => '0',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
            ]);
        }
    }
}
