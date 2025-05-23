<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Verifikator extends Model
{
    use HasFactory;

    protected $table = 'view_head';

    public function getkodeAttribute()
    {                

        $user = User::where('id',$this->verifikator)->first();
        return $user->roles->kode;   
    }

    public function doc()
    {   
        return $this->HasOne(Head::class, 'id', 'head');
    }
}
