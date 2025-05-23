<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class News extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['primary','kabid'];



    public function doc()
    {         
        return $this->belongsTo(Head::class, 'head', 'id'); 
    }

    public function getprimaryAttribute()
    {                
        $not = $this->not->where('grade', 1)->first();
        return $not->user->roles->kode;
    }

    public function getleaderAttribute()
    {                
        $not = $this->not->where('grade', 1)->first();
        return $not->user->name;
    }

    public function getkabidAttribute()
    {                
        $kb = User::whereHas('roles',function($q){
            $q->where('kode','KB');
        })->first();
        return (object) ['name'=>$kb->name, 'nip'=>$kb->nip];
    }

    public function not()
    {         
        return $this->HasMany(Notulen::class, 'head', 'head'); 
    }

    public function barp()
    {   
        return $this->HasOne(News::class, 'head', 'id');
    }

}
