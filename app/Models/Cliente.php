<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function compras()
    {
        return $this->hasMany(Compras::class, 'id_cliente');
    }

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
