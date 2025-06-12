<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PemohonHead extends Model
{
    use HasFactory;
    protected $table = 'pemohon_head';

    public function bak()
    {
        return $this->HasOne(News::class, 'head', 'head');
    }

    public function barp()
    {
        return $this->HasOne(Meet::class, 'head', 'head');
    }

    public function doc()
    {
        return $this->belongsTo(Head::class, 'head', 'id'); 
    }

    public function tax()
    {
        return $this->HasOne(Tax::class, 'head', 'head');
    }

    public function sign()
    {
        return $this->HasMany(Signed::class, 'head', 'head');
    }

    public function attach()
    {
        return $this->HasOne(Attach::class, 'head', 'head');
    }

}
