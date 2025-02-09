<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QRCode extends Model
{
    protected $fillable = [
        'user_id',
        'content',
        'options',
        'type'
    ];

    protected $casts = [
        'options' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
