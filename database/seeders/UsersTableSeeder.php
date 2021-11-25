<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['name'=>'jack','email'=>'jackymong@gmail.com','password'=>'12345'],
            ['name'=>'jami','email'=>'jami@gmail.com','password'=>'123456'],
            ['name'=>'Ukhaimi','email'=>'ukhaimi@gmail.com','password'=>'123457'],
        ];
        User::insert($users);
    }
}
