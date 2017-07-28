<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    const EMAIL = 'email';
    const TOKEN = 'token';

    protected $primaryKey = 'email';

    public $timestamps = false;

    protected $table = 'password_resets';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }
}
