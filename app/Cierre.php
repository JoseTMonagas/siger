<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cierre extends Model
{
    protected $fillable = ["empresa_id", "desde", "hasta", "monto"];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo("App\Empresa");
    }

    public function ordenesCompra(): HasMany
    {
        return $this->hasMany("App\OrdenCompra");
    }
}
