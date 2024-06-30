<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'item_id';  // Add this line

    protected $fillable = [
        'groupid', 'name', 'aanschafdatum', 'tiernummer', 'status', 'picture'
    ];
}


