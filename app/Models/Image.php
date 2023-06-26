<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $guarded =[''];

    public function attachable()
    {
        return $this->morphTo(); // 為一對"多"的時候，取得"多"的資料
    }
}
