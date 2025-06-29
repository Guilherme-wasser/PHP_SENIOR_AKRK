<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Import;
use App\Jobs\GenerateCnabJob;

class ImportController extends Controller
{
    /* GET /imports  ─────────────── */
    public function index(Request $request)
    {
        $query = Import::with(['fund','user']);

        // filtros opcionais
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return $query->orderByDesc('id')
                     ->paginate($request->get('per_page', 15));
    }

    /* POST /imports ─────────────── */
    public function store(Request $request)
    {
        $data = $request->validate([
            'fund_id'   => ['required','exists:funds,id'],
            'sequence'  => ['required','digits:4'],
            'file'      => ['required','file','mimes:xlsx,csv'],
        ]);

        // 1. Salva arquivo original
        $path = $request->file('file')
                        ->store('imports/originals');

        // 2. Cria registro pendente
        $import = Import::create([
            'user_id'       => Auth::id(),
            'fund_id'       => $data['fund_id'],
            'sequence'      => $data['sequence'],
            'original_file' => $path,
            'status'        => 'pending',
        ]);

        // 3. Despacha Job
        GenerateCnabJob::dispatch($import);

        return response()->json($import, 201);
    }

    /* GET /imports/{id}/download/excel ───── */
    public function downloadExcel(Import $import)
    {
        return Storage::download($import->original_file);
    }

    /* GET /imports/{id}/download/cnab ─────── */
    public function downloadCnab(Import $import)
    {
        abort_if($import->status !== 'done', 404);
        return Storage::download($import->cnab_file, "CNAB_{$import->sequence}.txt");
    }
}
