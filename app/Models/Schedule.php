<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    public function doc()
    {
        return $this->belongsTo(Head::class, 'head', 'id');
    }

    public function getnumberAttribute()
    {
        $tang = explode('-',$this->tanggal);
        $nom = str_pad($this->id, 4, '0', STR_PAD_LEFT);
        return '600.1.15/PBLT/' . $nom . '/Und-SIMBG/' . numberToRoman($tang[1]) . '/' . $tang[0];
    }
}
