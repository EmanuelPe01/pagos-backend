<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compras extends Model
{
    use HasFactory;

    protected $fillable = [
        'mount',
        'id_cliente',
        'id_producto'
    ];

    public function clientes()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    public function pagos()
    {
        return $this->hasMany(Pagos::class, 'id_compra');
    }

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
