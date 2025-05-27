<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pickup extends Model
{
    protected $fillable = [
        'user_id',
        'pickup_date',
        'location',
        'status',
        'collector_id',
        'pickup_image',
        'description',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function collector(){
        return $this->belongsTo(User::class, 'collector');
    }
}
