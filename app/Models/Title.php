<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    use HasFactory;

    public function document()
    {
        return $this->belongsTo(Formulir::class, 'doc', 'id');
    }

    public function items()
    {
        return $this->HasMany(Item::class, 'titles_id', 'id');
    }
}
