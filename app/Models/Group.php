<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'group'; // Specify the correct table name

    protected $fillable = ['name'];

    public function items()
    {
        return $this->hasMany(ItemId::class, 'groupid', 'id');
    }
}


