<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemId extends Model
{
    use HasFactory;

    protected $table = 'item_id'; // Specify the correct table name

    protected $fillable = [
        'groupid', 'name', 'aanschafdatum', 'tiernummer', 'status', 'picture'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'groupid', 'id');
    }
}
