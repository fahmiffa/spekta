<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;

    public function kecamatan()
    {
        return $this->belongsTo(District::class, 'districts_id', 'id');
    }
}
