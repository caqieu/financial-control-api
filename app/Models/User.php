<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasApiTokens;
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'email',
        'password'
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function incomes(): HasMany
    {
        return $this->hasMany(Incomes::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expenses::clas);
    }
}
