<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Permission;

class Role extends Model    
{
    use HasFactory, SoftDeletes;

    public function user()
    {
        return $this->HasMany(User::class, 'role','id');     
    }

    public function permit()
    {
        return $this->permission;
    }

    public function getLevelAttribute()
    {

    }




}
