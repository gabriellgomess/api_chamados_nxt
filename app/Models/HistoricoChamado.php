<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="HistoricoChamado",
 *     type="object",
 *     required={"chamado_id", "usuario_id", "acao", "descricao"},
 *     @OA\Property(property="id", type="integer", readOnly=true),
 *     @OA\Property(property="chamado_id", type="integer"),
 *     @OA\Property(property="usuario_id", type="integer"),
 *     @OA\Property(property="acao", type="string", enum={"criacao", "atualizacao", "transferencia", "resolucao", "fechamento"}),
 *     @OA\Property(property="descricao", type="string"),
 *     @OA\Property(property="dados_anteriores", type="object", nullable=true),
 *     @OA\Property(property="dados_novos", type="object", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", readOnly=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", readOnly=true),
 *     @OA\Property(property="chamado", ref="#/components/schemas/Chamado"),
 *     @OA\Property(property="usuario", ref="#/components/schemas/User")
 * )
 */
class HistoricoChamado extends Model
{
    use HasFactory;

    protected $table = 'historico_chamados';

    protected $fillable = [
        'chamado_id',
        'usuario_id',
        'acao',
        'descricao',
        'dados_anteriores',
        'dados_novos'
    ];

    protected $casts = [
        'dados_anteriores' => 'array',
        'dados_novos' => 'array'
    ];

    // Relacionamentos
    public function chamado()
    {
        return $this->belongsTo(Chamado::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    // Tipos de ações possíveis
    const ACAO_CRIACAO = 'criacao';
    const ACAO_ATUALIZACAO = 'atualizacao';
    const ACAO_TRANSFERENCIA = 'transferencia';
    const ACAO_RESOLUCAO = 'resolucao';
    const ACAO_FECHAMENTO = 'fechamento';
}
