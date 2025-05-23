<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    public function title()
    {
        return $this->belongsTo(Title::class, 'titles_id', 'id');
    }

    public function sub()
    {
        return $this->HasMany(Sub::class, 'items_id', 'id');
    }
}
