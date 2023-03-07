<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Record extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'type',
        'from_wallet_id',
        'to_wallet_id',
        'amount',
        'extra_type',
        'extra_percentage',
        'extra_amount',
        'date',
        'time',
        'datetime',
        'note',
        'timezone'
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
    //

    /**
     * Foreign Key Relation
     * 
     * @return model
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'category_id');
    }
    public function fromWallet()
    {
        return $this->belongsTo(\App\Models\Wallet::class, 'from_wallet_id');
    }
    public function toWallet()
    {
        return $this->belongsTo(\App\Models\Wallet::class, 'to_wallet_id');
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
    public function scopeGetRelated()
    {
        $related = $this->type;
        if(!empty($this->to_wallet_id)){
            $related = $this->where('user_id', $this->user_id)
                ->where('type', $this->type === 'expense' ? 'income' : 'expense')
                ->where('from_wallet_id', $this->to_wallet_id)
                ->where('to_wallet_id', $this->from_wallet_id)
                ->where('amount', $this->amount)
                ->where('extra_type', $this->extra_type)
                ->where('extra_percentage', $this->extra_percentage)
                ->where('extra_amount', $this->extra_amount)
                ->where('note', $this->note)
                ->where('datetime', $this->datetime)
                ->where('updated_at', $this->updated_at)
                ->first();
        }

        return $related;
    }
}
