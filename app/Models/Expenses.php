<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expenses extends Patrimony
{
    use HasFactory;

    const CATEGORIES = [
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
        'categoria',
        'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::creating(function ($data) {
            $category = $data['categoria'] ?? 'outras';

            $data['categoria'] = self::CATEGORIES[$category];
        });

        static::updating(function ($data) {
            $data['categoria'] = self::CATEGORIES[$data['categoria']];
        });
    }
}
