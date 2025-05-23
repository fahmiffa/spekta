<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Meet extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['primary','kabid'];

    public function doc()
    {         
        return $this->belongsTo(Head::class, 'head', 'id'); 
    }

    public function not()
    {         
        return $this->HasMany(Notulen::class, 'head', 'head'); 
    }

    public function getprimaryAttribute()
    {                
        $not = $this->not->where('grade', 1)->first();
        return $not->user->roles->kode;
    }

    public function getkabidAttribute()
    {                
        $kb = User::whereHas('roles',function($q){
            $q->where('kode','KB');
        })
        ->first();
        return $kb->name;
    }
}
