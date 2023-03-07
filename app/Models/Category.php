<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'parent_id',
        'name',
        'icon',
        'color',
        'order',
        'order_main'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        // 
    ];
    
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Primary Key Relation
     * 
     * @return model
     */
    public function child()
    {
        return $this->hasMany(\App\Models\Category::class, 'parent_id');
    }
    public function record()
    {
        return $this->hasMany(\App\Models\Record::class, 'category_id');
    }

    /**
     * Foreign Key Relation
     * 
     * @return model
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
    public function parent()
    {
        return $this->belongsTo(\App\Models\Category::class, 'parent_id');
    }

    /**
     * The "boot" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Listen to Create Event
        static::creating(function ($model) {
            // Always generate UUID on Data Create
            $model->{'uuid'} = (string) Str::uuid();
        });
    }

    /**
     * Scope
     * 
     */
    public function scopeGetDefaultCategory()
    {
        return [
            [
                'name' => 'Entertainment',
                'icon' => null,
                'color' => null,
                'sub' => [
                    [
                        'name' => 'Beauty',
                        'icon' => null,
                        'color' => null,
                    ], [
                        'name' => 'Hobbies',
                        'icon' => null,
                        'color' => null,
                    ], [
                        'name' => 'Holiday',
                        'icon' => null,
                        'color' => null,
                    ], [
                        'name' => 'Shopping',
                        'icon' => null,
                        'color' => null,
                    ], [
                        'name' => 'Sport',
                        'icon' => null,
                        'color' => null,
                    ], [
                        'name' => 'Subscription',
                        'icon' => null,
                        'color' => null,
                    ],
                ],
            ], [
                'name' => 'Food & Drink',
                'icon' => null,
                'color' => null,
                'sub' => [
                    [
                        'name' => 'Cafe',
                        'icon' => null,
                        'color' => null,
                    ], [
                        'name' => 'Groceries',
                        'icon' => null,
                        'color' => null,
                    ],
                ],
            ], [
                'name' => 'Housing',
                'icon' => null,
                'color' => null,
                'sub' => [
                    [
                        'name' => 'Energy',
                        'icon' => null,
                        'color' => null,
                    ], [
                        'name' => 'Maintenance',
                        'icon' => null,
                        'color' => null,
                    ], [
                        'name' => 'Rent',
                        'icon' => null,
                        'color' => null,
                    ],
                ],
            ], [
                'name' => 'Transportation',
                'icon' => null,
                'color' => null,
                'sub' => [
                    [
                        'name' => 'Business Trip',
                        'icon' => null,
                        'color' => null,
                    ], [
                        'name' => 'Public Transport',
                        'icon' => null,
                        'color' => null,
                    ], [
                        'name' => 'Taxi',
                        'icon' => null,
                        'color' => null,
                    ],
                ],
            ], [
                'name' => 'Vehicle',
                'icon' => null,
                'color' => null,
                'sub' => [
                    [
                        'name' => 'Fuel',
                        'icon' => null,
                        'color' => null,
                    ], [
                        'name' => 'Maintenance',
                        'icon' => null,
                        'color' => null,
                    ], [
                        'name' => 'Parking',
                        'icon' => null,
                        'color' => null,
                    ], [
                        'name' => 'Insurance',
                        'icon' => null,
                        'color' => null,
                    ],
                ],
            ], [
                'name' => 'Income',
                'icon' => null,
                'color' => null,
                'sub' => [
                    [
                        'name' => 'Child Support',
                        'icon' => null,
                        'color' => null,
                    ], [
                        'name' => 'Gift',
                        'icon' => null,
                        'color' => null,
                    ], [
                        'name' => 'Invoice',
                        'icon' => null,
                        'color' => null,
                    ], [
                        'name' => 'Salary',
                        'icon' => null,
                        'color' => null,
                    ], [
                        'name' => 'Other',
                        'icon' => null,
                        'color' => null,
                    ],
                ],
            ], [
                'name' => 'Expense',
                'icon' => null,
                'color' => null,
                'sub' => [
                    [
                        'name' => 'Charge',
                        'icon' => null,
                        'color' => null,
                    ], [
                        'name' => 'Fines',
                        'icon' => null,
                        'color' => null,
                    ], [
                        'name' => 'Tax',
                        'icon' => null,
                        'color' => null,
                    ], [
                        'name' => 'Other',
                        'icon' => null,
                        'color' => null,
                    ],
                ],
            ], [
                'name' => 'Other',
                'icon' => null,
                'color' => null,
                'sub' => [
                    [
                        'name' => 'Missing',
                        'icon' => null,
                        'color' => null,
                    ],
                ],
            ],
        ];
    }
    public function scopeGetLastTransaction($query)
    {
        return $this->record()->orderBy('datetime', 'desc')->first() ?? null;
    }
}
