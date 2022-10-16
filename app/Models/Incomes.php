<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Incomes extends Patrimony
{
    use HasFactory;

    protected $table = 'receitas';
}
