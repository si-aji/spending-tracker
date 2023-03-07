<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SeederUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        \App\Models\Record::truncate();
        \App\Models\Wallet::truncate();
        \App\Models\Category::truncate();
        \App\Models\User::truncate();
        Schema::enableForeignKeyConstraints();

        $data = [
            new \App\Models\User([
                'name' => 'Dwi Aji',
                'email' => 'dwiaji@mail.tld',
                'username' => 'dwiaji',
                'password' => \Hash::make('dwiaji'),
            ]),
        ];

        if(count($data)){
            foreach($data as $user){
                $user->save();
                event(new \App\Events\UserRegistered($user));
            }
        }
    }
}
