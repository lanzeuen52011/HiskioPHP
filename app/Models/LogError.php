<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogError extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = ['']; // 此處為黑名單
    protected $casts = [ // 這個屬性在被處理時，會被當作是甚麼資料類型，預設都為string，若是created類型就會是timestamp或者是datetime
        'trace' => 'array', // 'trace'先存為Array，後續再轉成json，先弄成Array以方便轉換，或者從資料庫的json拿出來，也可以直接轉成Array易於後端操作
        'params' => 'array',
        'header' => 'array'
    ];
}
