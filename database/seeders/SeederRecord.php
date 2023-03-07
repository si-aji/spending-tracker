<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SeederRecord extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timezone = config('app.timezone');

        Schema::disableForeignKeyConstraints();
        \App\Models\Record::truncate();
        Schema::enableForeignKeyConstraints();

        for($i = 0; $i < 1; $i++){
            $user = \App\Models\User::inRandomOrder()->first();
            if(!empty($user)){
                $category = \App\Models\Category::where('user_id', $user->id)
                    ->inRandomOrder()
                    ->first();
                // $type = ['expense', 'income', 'transfer'];
                // $type = ['expense', 'income'];
                $type = ['income'];
                $type = $type[array_rand($type)];
                $amount = rand(1, 1000) * 10000;
                // Extra Amount
                $extraType = ['amount', 'percentage'];
                $extraType = $extraType[array_rand($extraType)];
                $extraPercentage = 0;
                $extraAmount = rand(0, 100) * 10000;
                if($extraType === 'percentage'){
                    $extraPercentage = rand(1, 25);
                    $extraAmount = $extraPercentage * $amount;
                }
                $date = date('Y-m-d H:i:s');
                // Convert datetime
                $datetime = date("Y-m-d H:i:00", strtotime($date));
                if(!empty($timezone)){
                    $timezone = $timezone;
                    $formated = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $datetime, $timezone);
                    $datetime = $formated->setTimezone('UTC')->format('Y-m-d H:i:s');
                }

                $fromWallet = \App\Models\Wallet::where('user_id', $user->id)
                    ->inRandomOrder()
                    ->first();

                // Fetch Wallet
                if(!empty($fromWallet)){
                    if($type === 'transfer'){
                        $toWallet = \App\Models\Wallet::where('user_id', $user->id)
                            ->where('id', '!=', $fromWallet->id)
                            ->inRandomOrder()
                            ->first();
    
                        if(!empty($toWallet)){
                            foreach(['expense', 'income'] as $type){
                                $record = new \App\Models\Record([
                                    'user_id' => $user->id,
                                    'category_id' => $category->id,
                                    'type' => $type,
                                    'from_wallet_id' => $type === 'expense' ? $fromWallet->id : $toWallet->id,
                                    'to_wallet_id' => $type === 'expense' ? $toWallet->id : $fromWallet->id,
                                    'amount' => $amount,
                                    'extra_type' => $extraType,
                                    'extra_percentage' => $extraPercentage,
                                    'extra_amount' => $extraAmount,
                                    'date' => date('Y-m-d', strtotime($datetime)),
                                    'time' => date('H:i:s', strtotime($datetime)),
                                    'datetime' => date('Y-m-d H:i:s', strtotime($datetime)),
                                    'note' => 'Sample data from Seeder #'.$i,
                                    'timezone' => $timezone
                                ]);
                                $record->save();
                            }
                        }
                    } else {
                        $record = new \App\Models\Record([
                            'user_id' => $user->id,
                            'category_id' => $category->id,
                            'type' => $type,
                            'from_wallet_id' => $fromWallet->id,
                            'to_wallet_id' => null,
                            'amount' => $amount,
                            'extra_type' => $extraType,
                            'extra_percentage' => $extraPercentage,
                            'extra_amount' => $extraAmount,
                            'date' => date('Y-m-d', strtotime($datetime)),
                            'time' => date('H:i:s', strtotime($datetime)),
                            'datetime' => date('Y-m-d H:i:s', strtotime($datetime)),
                            'note' => 'Sample data from Seeder #'.$i,
                            'timezone' => $timezone
                        ]);
                        $record->save();
                    }
                }
            }
        }
    }
}
