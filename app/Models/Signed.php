<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Signed extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'signed';


    public function doc()
    {         
        return $this->belongsTo(Head::class, 'head', 'id'); 
    }

    public function kons()
    {         
        return $this->belongsTo(Consultation::class, 'head', 'head'); 
    }

    public function users()
    {   
        return $this->HasOne(User::class, 'id', 'user'); 
    }
}
