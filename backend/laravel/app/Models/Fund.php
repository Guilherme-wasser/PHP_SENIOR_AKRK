<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cnpj',
        'street',
        'number',
    ];

    /**
     * Cada fundo pode ter vários mapeamentos (De/Para)
     */
    public function mappings()
    {
        return $this->hasMany(\App\Models\Mapping::class);
    }

    /**
     * Helper: busca um valor específico do mapping
     * Exemplo: $fund->mapping('bank_code') => '341'
     */
    public function mapping(string $key): ?string
    {
        return $this->mappings()->where('key', $key)->value('value');
    }
}
