<?php

namespace App\Models;

class Incomes extends Patrimony
{
    protected $table = 'receitas';

    public function formatFields(array $data)
    {
        return $data;
    }
}
