<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    use HasFactory;

    public function document()
    {
        return $this->belongsTo(Document::class, 'doc', 'id');
    }
}
