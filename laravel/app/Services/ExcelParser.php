<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ExcelParser
{
    /**
     * Lê um arquivo Excel salvo no disco “local” e devolve as linhas.
     */
    public static function rows(string $diskPath): Collection
    {
        // caminho absoluto: ex. /var/www/html/storage/app/imports/originals/abc.xlsx
        $fullPath = Storage::path($diskPath);

        if (! file_exists($fullPath)) {
            throw new \RuntimeException("Arquivo [$fullPath] não encontrado.");
        }

        /** @var \Illuminate\Support\Collection $rows */
        $rows = Excel::toCollection(null, $fullPath)->first();

        // remove cabeçalho se a primeira célula contém “contrato”
        if ($rows->first() && str_contains(strtolower($rows->first()[0]), 'contrato')) {
            $rows->shift();
        }

        return $rows;
    }
}
