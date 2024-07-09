<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Setor",
 *     type="object",
 *     required={"centro_de_custo_id", "nome", "descricao", "codigo"},
 *     @OA\Property(property="id", type="integer", readOnly=true),
 *     @OA\Property(property="centro_de_custo_id", type="integer"),
 *     @OA\Property(property="nome", type="string"),
 *     @OA\Property(property="descricao", type="string"),
 *     @OA\Property(property="codigo", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time", readOnly=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", readOnly=true)
 * )
 */

class Setor extends Model
{
    use HasFactory;

    protected $table = 'setores';

    protected $fillable = [
        'centro_de_custo_id', 'nome', 'descricao', 'codigo'
    ];

    public function centroDeCusto()
    {
        return $this->belongsTo(CentroDeCusto::class);
    }
}
