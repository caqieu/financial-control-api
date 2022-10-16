<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class Patrimony extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'descricao',
        'valor',
        'data'
    ];

    protected $casts = [
        'data' => 'datetime:Y-m-d',
        'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d h:i:s',
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];
}
