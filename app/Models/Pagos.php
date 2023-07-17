<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagos extends Model
{
    use HasFactory;

    protected $fillable = [
        'mount',
        'id_compra',
    ];

    public function compras()
    {
        return $this->belongsTo(Compras::class, 'id_compra');
    }

    protected $hidden = [
        'updated_at'
    ];
}
