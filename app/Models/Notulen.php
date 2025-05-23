<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Notulen extends Model
{
    use HasFactory;

    protected $table = 'view_consultations';

    protected $appends = ['sign'];

    public function getsignAttribute()
    {                
        if($this->grade == 1 && $this->user->roles->kode == 'TPT')
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function doc()
    {   
        return $this->HasOne(Head::class, 'id', 'head'); 
    }

    public function user()
    {   
        return $this->HasOne(User::class, 'id', 'users'); 
    }

    public function barp()
    {   
        return $this->HasOne(Meet::class, 'head', 'head');
    }

    public function bak()
    {   
        return $this->HasOne(News::class, 'head', 'head');
    }
}
