<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Wallet extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'parent_id',
        'name',
        'type',
        'starting_balance',
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
        return $this->hasMany(\App\Models\Wallet::class, 'parent_id');
    }
    public function record()
    {
        return $this->hasMany(\App\Models\Record::class, 'from_wallet_id');
    }
    public function recordRelated()
    {
        return $this->hasOne(\App\Models\Record::class, 'to_wallet_id');
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
        return $this->belongsTo(\App\Models\Wallet::class, 'parent_id');
    }
    public function walletGroupItem()
    {
        return $this->belongsToMany(\App\Models\WalletGroup::class, (new \App\Models\WalletGroupItem())->getTable())
            ->using(\App\Models\WalletGroupItem::class)
            ->withPivot('created_at', 'updated_at')
            ->withTimestamps();
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
     */
    public function scopeGetBalance()
    {
        $startingBalance = 0;
        if(!empty($this->starting_balance)){
            $startingBalance = $this->starting_balance;
        }

        $balance = $this->record();

        // Sort balance
        $balance->orderBy('datetime', 'desc')
            ->orderBy('created_at', 'desc');

        return $startingBalance + $balance->sum(\DB::raw('(amount + extra_amount) * IF(type = "expense", -1, 1)'));
    }
    public function scopeGetLastTransaction($query)
    {
        $result = [];

        $data = $this->record()->orderBy('datetime', 'desc')->first();
        if(!empty($data)){
            $result = $data;
        }

        return $result;
    }
}
