<?php

// app/Models/Item.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'groupid', 'name', 'aanschafdatum', 'tiernummer', 'status', 'picture'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'groupid');
    }
}



