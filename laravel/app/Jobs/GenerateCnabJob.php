<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateCnabJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Import $import) {}

    public function handle(): void
    {
        // TODO: gerar CNAB
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
