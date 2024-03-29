<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use App\Services\PatrimonyService;

class ExpensesController extends PatrimonyController
{
    public function __construct()
    {
        $this->createRules = [
            'descricao' => 'required|string|max:45',
            'valor' => 'required|numeric|min:0|max:10000000',
            'data' => 'required|date',
            'categoria' => 'string
                |in:' . implode(',', array_keys(Expenses::CATEGORIES)) . '
                |nullable'
        ];

        $this->updateRules = [
            'descricao' => 'string|max:45',
            'valor' => 'numeric|min:0|max:10000000',
            'data' => 'date',
            'categoria' => 'string
                |in:' . implode(',', array_keys(Expenses::CATEGORIES)) . '
                |nullable'
        ];

        $service = new PatrimonyService(app(Expenses::class));

        parent::__construct($service, 'Despesa');
    }
}
