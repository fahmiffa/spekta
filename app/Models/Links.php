<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Links extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'links';

    public function doc()
    {         
        return $this->belongsTo(Head::class, 'head', 'id'); 
    }
}
