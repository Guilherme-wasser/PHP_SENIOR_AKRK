<?php

namespace App\Jobs;

use App\Models\Import;
use App\Services\CnabBuilder;
use App\Services\ExcelParser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GenerateCnabJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private Import $import)
    {
        $this->import->load('fund');
    }

    public function handle(CnabBuilder $builder): void
    {
        $this->import->update(['status' => 'processing']);

        /* ---------- AQUI: usa o caminho salvo em original_file ---------- */
        $rows = ExcelParser::rows($this->import->original_file);

        $cnab = $builder->build(
            fund:     $this->import->fund,
            rows:     $rows,
            sequence: $this->import->sequence
        );

        $filePath = "cnab/{$this->import->id}.txt";
        Storage::put($filePath, $cnab);

        $this->import->update([
            'status'    => 'done',
            'total'     => $rows->sum(fn ($r) => $r[2] * 100),
            'cnab_file' => $filePath,
        ]);
    }
}
