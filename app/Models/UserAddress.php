<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'name',
        'phone',
        'line1',
        'line2',
        'city',
        'state',
        'postcode',
        'country_code',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'bool',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
