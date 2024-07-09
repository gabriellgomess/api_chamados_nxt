<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Atendente",
 *     type="object",
 *     required={"nome", "email", "telefone"},
 *     @OA\Property(property="id", type="integer", readOnly=true),
 *     @OA\Property(property="nome", type="string"),
 *     @OA\Property(property="email", type="string"),
 *     @OA\Property(property="telefone", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time", readOnly=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", readOnly=true)
 * )
 */

class Atendente extends Model
{
    use HasFactory;

    protected $table = 'atendentes';

    protected $fillable = [
        'nome', 'email', 'telefone'
    ];
}
