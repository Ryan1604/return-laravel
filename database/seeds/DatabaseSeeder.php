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
        if(Schema::hasTable('faculties')) {
            if(DB::table('faculties')->count() > 0) {
                DB::table('faculties')->truncate();
            }

            DB::table('faculties')->insert([
                [
                    'name' => 'Civil Engineering',
                ],
                [
                    'name' => 'Electrical Engineering',
                ],
                [
                    'name' => 'General Mechanical Engineering',
                ],
                [
                    'name' => 'Automobile Engineering',
                ],
                [
                    'name' => 'Electronic Engineering',
                ],
                [
                    'name' => 'Computer Science',
                ],
                [
                    'name' => 'Optical Science',
                ],
                [
                    'name' => 'Culinary Art (Bakery & Cookery)',
                ],
                [
                    'name' => 'Tourism and Hospitality',
                ],
            ]);
        }

        if(Schema::hasTable('roles')) {
            if(DB::table('roles')->count() > 0) {
                DB::table('roles')->truncate();
            }

            DB::table('roles')->insert([
                [
                    'name' => 'Librarian',
                ],
                [
                    'name' => 'Chief of Library',
                ],
                [
                    'name' => 'Lecturer',
                ],
                [
                    'name' => 'Student',
                ],
            ]);
        }

        if(Schema::hasTable('users')) {
            if(DB::table('users')->count() > 0) {
                DB::table('users')->truncate();
            }

            DB::table('users')->insert([
                [
                    'sn' => 'npic123librarian',
                    'name' => 'Librarian',
                    'phone_number' => '0123-4567-89',
                    'dob' => '1990-01-01',
                    'address' => 'Phnom Penh',
                    'username' => 'librarian',
                    'email' => 'librarian@npic.com',
                    'password' => bcrypt('123'),
                    'profile_url' => 'admin.jpg',
                    'role_id' => 1,
                    'disabled' => '0',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'sn' => 'npic123chief',
                    'name' => 'Chief of Library',
                    'phone_number' => '0987-6543-21',
                    'dob' => '1990-01-01',
                    'address' => 'Phnom Penh',
                    'username' => 'chief',
                    'email' => 'chief@npic.com',
                    'password' => bcrypt('123'),
                    'profile_url' => 'default.png',
                    'role_id' => 2,
                    'disabled' => '0',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
            ]);
        }

        if(Schema::hasTable('penalty')) {
            if(DB::table('penalty')->count() > 0) {
                DB::table('penalty')->truncate();
            }

            DB::table('penalty')->insert([
                'price' => 500,
                'date' => Carbon::today()->toDateString(),
            ]);
        }

        if(Schema::hasTable('issue_rules')) {
            if(DB::table('issue_rules')->count() > 0) {
                DB::table('issue_rules')->truncate();
            }

            DB::table('issue_rules')->insert([
                [
                    'role_id' => 3,
                    'max_borrow_item' => 10,
                    'max_borrow_day' => 30,
                ],
                [
                    'role_id' => 4,
                    'max_borrow_item' => 2,
                    'max_borrow_day' => 14,
                ],
            ]);
        }
    }
}
