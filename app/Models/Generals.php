<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Generals extends Model
{
    use HasFactory;
    protected $table = 'generals';

    public function reff()
    {         
        return $this->belongsTo(News::class, 'bak', 'id'); 
    }

}
