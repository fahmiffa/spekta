<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    public function desa()
    {
        return $this->hasMany(Village::class, 'districts_id', 'id');  
    }
}
