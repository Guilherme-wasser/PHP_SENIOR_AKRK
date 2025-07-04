<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Import;
use App\Jobs\GenerateCnabJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ImportController extends Controller
{
    /**
     * Aplica autenticação JWT e exige papel “admin”.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /* --------------------------------------------------------------
     * GET /imports
     * ------------------------------------------------------------ */
    public function index(Request $request)
    {
        $imports = Import::with(['fund', 'user'])
            ->when($request->filled('sequence'), fn ($q) => $q->where('sequence', $request->sequence))
            ->when($request->filled('status'),     fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('date_from'),  fn ($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->filled('date_to'),    fn ($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->orderByDesc('id')
            ->paginate($request->integer('per_page', 15));

        return response()->json($imports);
    }

    /* --------------------------------------------------------------
     * POST /imports
     * ------------------------------------------------------------ */
    public function store(Request $request)
    {
        $data = $request->validate([
            'fund_id'  => ['required', 'exists:funds,id'],
            'sequence' => ['required', 'digits:4'],
            'file'     => ['required', 'file', 'mimes:xls,xlsx,csv'],
        ]);

        // 1. Armazena o arquivo original
        $path = $request->file('file')->store('imports/originals');

        // 2. Cria o registro de importação
        $import = Import::create([
            'user_id'       => Auth::id(),
            'fund_id'       => $data['fund_id'],
            'sequence'      => $data['sequence'],
            'original_file' => $path,
            'status'        => 'pending',
        ]);

        // 3. Dispara o job assíncrono para gerar o CNAB
        GenerateCnabJob::dispatch($import);

        return response()->json([
            'message'   => 'Importação recebida. Processamento em fila.',
            'import_id' => $import->id,
        ], 202);
    }

    /* --------------------------------------------------------------
     * GET /imports/{import}
     * ------------------------------------------------------------ */
    public function show(Import $import)
    {
        return response()->json($import->load(['fund', 'user']));
    }

    /* --------------------------------------------------------------
     * GET /imports/{import}/download/excel
     * ------------------------------------------------------------ */
    public function downloadExcel(Import $import)
    {
        return Storage::download($import->original_file);
    }

    /* --------------------------------------------------------------
     * GET /imports/{import}/download/cnab
     * ------------------------------------------------------------ */
    public function downloadCnab(Import $import)
    {
        abort_if($import->status !== 'done', 409, 'Arquivo CNAB ainda não gerado');

        return Storage::download($import->cnab_file, "CNAB_{$import->sequence}.txt");
    }
}
