<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    public function header()
    {
        return $this->HasOne(Header::class, 'doc','id');
    }

    public function footer()
    {
        return $this->HasOne(Footer::class, 'doc','id');
    }

    public function title()
    {
        return $this->HasMany(Title::class, 'doc','id');
    }
}
