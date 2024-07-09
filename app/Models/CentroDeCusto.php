<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="CentroDeCusto",
 *     type="object",
 *     required={"nome", "descricao", "codigo"},
 *     @OA\Property(property="id", type="integer", readOnly=true),
 *     @OA\Property(property="nome", type="string"),
 *     @OA\Property(property="descricao", type="string"),
 *     @OA\Property(property="codigo", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time", readOnly=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", readOnly=true)
 * )
 */
class CentroDeCusto extends Model
{
    use HasFactory;

    protected $table = 'centros_de_custo';

    protected $fillable = [
        'nome',
        'descricao',
        'codigo',
    ];
}
