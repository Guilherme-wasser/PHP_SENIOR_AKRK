<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapping extends Model
{
    protected $fillable = ['fund_id', 'key', 'value'];

    public function fund()
    {
        return $this->belongsTo(Fund::class);
    }
}
