<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consultation extends Model
{
    use HasFactory, SoftDeletes;

    public function getkonsAttribute()
    {                

       if($this->konsultan)
       {  
            $val = explode(",",$this->konsultan);
            
           foreach($val as $item)
            {
                $user = User::where('id',$item)->first();
                if($user)
                {
                    $name []= $user->name;
                }
            }
           
            return $name;
       } 
       else
       {
            return null;
       }
    }

    public function getnotulensAttribute()
    {                

       if($this->notulen)
       {  
            $val = explode(",",$this->notulen);
            
           foreach($val as $item)
            {
                $user = User::where('id',$item)->first();
                if($user)
                {
                    $name []= $user->name;
                }
            }
           
            return $name;
       } 
       else
       {
            return null;
       }
    }

    public function bak()
    {   
        return $this->HasOne(News::class, 'head', 'head');
    }

    public function sign()
    {   
        return $this->HasMany(Signed::class, 'head', 'head');
    }

    public function doc()
    {
        return $this->belongsTo(Head::class, 'head', 'id');   
    }

    public function not()
    {
        return $this->HasOne(User::class, 'id', 'notulen');   
    }
}
