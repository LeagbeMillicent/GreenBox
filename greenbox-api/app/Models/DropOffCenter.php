<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DropOffCenter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'location',
        'contact_number',
        'operating_hours',
        'is_active',
    ];
}
