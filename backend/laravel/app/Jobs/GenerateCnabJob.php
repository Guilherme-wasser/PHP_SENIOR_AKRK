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

    /** @var Import */
    private Import $import;

    /** Linhas da planilha já carregadas */
    private \Illuminate\Support\Collection $rows;

    public function __construct(Import $import)
    {
        // carrega o relacionamento fund logo de cara
        $this->import = $import->load('fund');
    }

    public function handle(CnabBuilder $builder): void
    {
        // 1) marca como “processing”
        $this->import->update(['status' => 'processing']);

        // 2) lê as linhas da planilha (uma vez só)
        $this->rows = ExcelParser::rows($this->import->original_file);

        // 3) calcula totais que o trailer precisa
        $totalCentavos = $this->rows->sum(fn ($r) => $r[2] * 100);  
        $qtdeDetalhes  = $this->rows->count();                      

        // 4) monta o CNAB (passando os dados ao builder)
        $cnab = $builder->build(
            fund          : $this->import->fund,
            rows          : $this->rows,
            sequence      : $this->import->sequence,
            totalCentavos : $totalCentavos,   
            qtdeDetalhes  : $qtdeDetalhes     
        );

        // 5) grava o arquivo em disk “local” (storage/app/…)
        $filePath = "cnab/{$this->import->id}.txt";
        Storage::disk('local')->put($filePath, $cnab);

        // 6) finaliza o registro
        $this->import->update([
            'status'    => 'done',
            'total'     => $totalCentavos,
            'cnab_file' => $filePath,
        ]);
    }
}
