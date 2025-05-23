<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SpjSub extends Model
{
    use HasFactory;
    protected $table = 'spj_sub';

    public function doc()
    {         
        return $this->belongsTo(PemohonHead::class, 'head', 'head'); 
    }

}
