<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GenerateDefaultCategory
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        if(!$event->user->category()->exists()){
            // Fetch default category
            $category = new \App\Models\Category();
            $default = $category->getDefaultCategory();

            $order_main = 0;
            // Generate
            if(!empty($default) && is_array($default)){
                foreach($default as $item){
                    $order = 0;
                    // Create category
                    $parent = new \App\Models\Category([
                        'user_id' => $event->user->id,
                        'parent_id' => null,
                        'name' => $item['name'],
                        'icon' => $item['icon'],
                        'color' => $item['color'],
                        'order' => $order,
                        'order_main' => $order_main
                    ]);
                    $parent->save();

                    // Generate Child Category
                    if(isset($item['sub']) && is_array($item['sub']) && !empty($item['sub'])){
                        foreach($item['sub'] as $itemChild){
                            $order += 1;
                            $order_main += 1;

                            // Create child category
                            $child = new \App\Models\Category([
                                'user_id' => $event->user->id,
                                'parent_id' => $parent->id,
                                'name' => $itemChild['name'],
                                'icon' => $itemChild['icon'],
                                'color' => $itemChild['color'],
                                'order' => $order,
                                'order_main' => $order_main
                            ]);
                            $child->save();
                        }
                    }

                    $order_main += 1;
                }
            }
        }
    }
}
