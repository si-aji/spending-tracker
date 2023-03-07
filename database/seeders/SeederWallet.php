<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SeederWallet extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        \App\Models\Wallet::truncate();
        Schema::enableForeignKeyConstraints();

        $data = [
            new \App\Models\Wallet([
                'user_id' => 'dwiaji@mail.tld',
                'parent_id' => null,
                'name' => 'Cash',
                'type' => 'general',
                'starting_balance' => 0,
                'order' => 0,
                'order_main' => 0
            ]), new \App\Models\Wallet([
                'user_id' => 'dwiaji@mail.tld',
                'parent_id' => null,
                'name' => 'BCA',
                'type' => 'general',
                'starting_balance' => 0,
                'order' => 0,
                'order_main' => 0
            ]), new \App\Models\Wallet([
                'user_id' => 'dwiaji@mail.tld',
                'parent_id' => 'BCA',
                'name' => 'Mamah',
                'type' => 'general',
                'starting_balance' => 0,
                'order' => 0,
                'order_main' => 0
            ]), new \App\Models\Wallet([
                'user_id' => 'dwiaji@mail.tld',
                'parent_id' => null,
                'name' => 'Mandiri',
                'type' => 'general',
                'starting_balance' => 0,
                'order' => 0,
                'order_main' => 0
            ]), new \App\Models\Wallet([
                'user_id' => 'dwiaji@mail.tld',
                'parent_id' => 'Mandiri',
                'name' => 'Mamah',
                'type' => 'general',
                'starting_balance' => 0,
                'order' => 0,
                'order_main' => 0
            ]), new \App\Models\Wallet([
                'user_id' => 'dwiaji@mail.tld',
                'parent_id' => 'BCA',
                'name' => 'Sangu Hanif',
                'type' => 'general',
                'starting_balance' => 0,
                'order' => 0,
                'order_main' => 0
            ]), 
        ];

        $orderMain = 0;
        foreach($data as $wallet){
            $user = \App\Models\User::where('email', $wallet->user_id)
                ->first();
            if(!empty($user)){
                $parent_id = null;
                $order = 0;
                if(!empty($wallet->parent_id)){
                    $parent = \App\Models\Wallet::where('name', $wallet->parent_id)
                        ->first();
                    
                    if(!empty($parent)){
                        // Get Last order
                        $lastOrder = \App\Models\Wallet::where('user_id', $user->id)
                            ->where('parent_id', $parent->id)
                            ->orderBy('order', 'desc')
                            ->first();
                        if(!empty($lastOrder)){
                            $order = $lastOrder->order;
                        }

                        $wallet->parent_id = $parent->id;
                        $wallet->order = $order + 1;
                    } else {
                        $wallet->parent_id = null;
                        $wallet->order = 0;
                    }
                }

                $wallet->user_id = $user->id;
                $wallet->order_main = $orderMain;
                $wallet->save();

                $orderMain++;
            }
        }
    }
}
