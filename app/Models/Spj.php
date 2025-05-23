<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Spj extends Model
{
    use HasFactory;
    protected $table = 'spj';

    public function data()
    {
        return $this->HasMany(SpjSub::class, 'head', 'id');
    }

    public function pelapor()
    {   
        return $this->HasOne(User::class, 'id', 'report'); 
    }

    public function sub()
    {
        return $this->HasMany(SpjSub::class, 'spj', 'id');
    }

}
