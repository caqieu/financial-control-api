<?php

namespace App\Http\Controllers;

use App\Models\Incomes;

class IncomesController extends PatrimonyController
{
    public function __construct()
    {
        $this->createRules = [
            'descricao' => 'required|string|max:45',
            'valor' => 'required|numeric|min:0|max:10000000',
            'data' => 'required|date'
        ];

        $this->updateRules = [
            'descricao' => 'string|max:45',
            'valor' => 'numeric|min:0|max:10000000',
            'data' => 'date'
        ];

        parent::__construct(app(Incomes::class), 'Receita');
    }
}
