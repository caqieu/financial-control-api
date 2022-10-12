<?php

namespace App\Models;

class Expenses extends Patrimony
{
    const CATEGORIES_VALIDATION = [
        'alimentacao' => 'Alimentação',
        'saude' => 'Saúde',
        'moradia' => 'Moradia',
        'transporte' => 'Transporte',
        'educacao' => 'Educação',
        'lazer' => 'Lazer',
        'imprevistos' => 'Imprevistos',
        'outras' => 'Outras'
    ];

    protected $table = 'despesas';

    protected $fillable = [
        'id',
        'descricao',
        'valor',
        'data',
        'categoria'
    ];

    public function formatFields(array $data)
    {
        $data['categoria'] = $data['categoria'] ?? 'outras';

        $data['categoria'] = self::CATEGORIES_VALIDATION[$data['categoria']];

        return $data;
    }
}
