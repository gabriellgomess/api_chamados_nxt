<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="SetorAtendente",
 *     type="object",
 *     required={"setor_id", "atendente_id", "is_gestor"},
 *     @OA\Property(property="id", type="integer", readOnly=true),
 *     @OA\Property(property="setor_id", type="integer"),
 *     @OA\Property(property="atendente_id", type="integer"),
 *     @OA\Property(property="is_gestor", type="boolean"),
 *     @OA\Property(property="created_at", type="string", format="date-time", readOnly=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", readOnly=true)
 * )
 */

class SetorAtendente extends Model
{
    use HasFactory;

    protected $table = 'setores_atendentes';

    protected $fillable = [
        'setor_id', 'atendente_id', 'is_gestor'
    ];

    public function setor()
    {
        return $this->belongsTo(Setor::class);
    }

    public function atendente()
    {
        return $this->belongsTo(Atendente::class);
    }
}
