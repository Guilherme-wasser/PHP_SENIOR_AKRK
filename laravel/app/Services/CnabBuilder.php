<?php

namespace App\Services;

use Illuminate\Support\Collection;
use App\Models\Fund;

class CnabBuilder
{
    public function build(Fund $fund, Collection $rows, int $sequence): string
    {
        $lines   = [];

        // 1. Cabeçalho 40 col.
        $lines[] = str_pad(substr($fund->name,0,10),10) .
                   str_pad(preg_replace('/\D/','',$fund->cnpj),14) .
                   str_pad(substr($fund->street,0,10),10) .
                   str_pad($fund->number, 3, '0', STR_PAD_LEFT) .
                   str_pad($sequence,3,'0',STR_PAD_LEFT);          // =40

        // 2. Corpo
        foreach ($rows as $row) {
            [$contract,$client,$value,$date] = $row;

            $lines[] = str_pad($contract,6,'0',STR_PAD_LEFT).
                       str_pad(substr($client,0,22),22).
                       str_pad(number_format($value*100,0,'',''),6,'0',STR_PAD_LEFT).
                       \Carbon\Carbon::parse($date)->format('Ymd');  // =40
        }

        // 3. Rodapé
        $total = $rows->sum(fn($r)=> $r[2]*100);
        $lines[] = str_pad($total,11,'0',STR_PAD_LEFT) .
                   '341' .            // banco
                   '12345' .          // agencia
                   '987651';          // conta

        return implode("\n", $lines);
    }
}
