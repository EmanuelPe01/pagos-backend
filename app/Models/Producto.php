<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price'
    ];

    public function compra()
    {
        return $this->hasMany(Compras::class, 'id_producto');
    }

    protected $hidden = [
        'updated_at'
    ];
}
