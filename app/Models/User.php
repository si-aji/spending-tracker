<?php

namespace App\Models;

use Illuminate\Support\Str;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
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
    public function wallet()
    {
        return $this->hasMany(\App\Models\Wallet::class, 'user_id');
    }
    public function category()
    {
        return $this->hasMany(\App\Models\Category::class, 'user_id');
    }
    public function record()
    {
        return $this->hasMany(\App\Models\Record::class, 'user_id');
    }
    public function walletGroup()
    {
        return $this->hasMany(\App\Models\WalletGroup::class, 'user_id');
    }

    /**
     * Foreign Key Relation
     * 
     * @return model
     */
    //

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
    public function scopeGetAvatar()
    {
        return getAvatar($this->name);
    }
    public function scopeGetFirstYear()
    {
        return date('Y', strtotime('2021-01-01'));
    }
}
