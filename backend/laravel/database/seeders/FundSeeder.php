<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FundSeeder extends Seeder
{
    public function run(): void
    {
        $funds = [
            [
                'name'   => 'Fundo Alpha',
                'cnpj'   => '12.345.678/0001-01',
                'street' => 'Rua das Árvores',
                'number' => '123',
            ],
            [
                'name'   => 'Fundo Beta',
                'cnpj'   => '98.765.432/0001-02',
                'street' => 'Av. Brasil',
                'number' => '456',
            ],
            [
                'name'   => 'Fundo Gama',
                'cnpj'   => '11.222.333/0001-03',
                'street' => 'Alameda Santos',
                'number' => '789',
            ],
        ];

        foreach ($funds as $fund) {
            DB::table('funds')->updateOrInsert(
                ['name' => $fund['name']], // condição WHERE
                $fund                       // valores a inserir/atualizar
            );
        }
    }
}
