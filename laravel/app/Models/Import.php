<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'fund_id', 'sequence',
        'original_file', 'cnab_file', 'status',
    ];

    /* ─── Relações ──────────────────────── */
    public function user()  { return $this->belongsTo(User::class); }
    public function fund()  { return $this->belongsTo(Fund::class); }
}
