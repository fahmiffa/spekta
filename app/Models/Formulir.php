<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formulir extends Model
{
    use HasFactory;

    protected $table = 'documents';

    public function header()
    {
        return $this->HasOne(Header::class, 'doc', 'id');
    }

    public function title()
    {
        return $this->HasMany(Title::class, 'doc', 'id');
    }
}
