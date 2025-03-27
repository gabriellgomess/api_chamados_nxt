<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Chamado",
 *     type="object",
 *     required={"titulo", "descricao", "setor_id", "prioridade"},
 *     @OA\Property(property="id", type="integer", readOnly=true),
 *     @OA\Property(property="titulo", type="string", maxLength=255),
 *     @OA\Property(property="descricao", type="string"),
 *     @OA\Property(property="status", type="string", enum={"aberto", "em_andamento", "transferido", "resolvido", "fechado"}),
 *     @OA\Property(property="prioridade", type="string", enum={"baixa", "media", "alta", "urgente"}),
 *     @OA\Property(property="setor_id", type="integer"),
 *     @OA\Property(property="solicitante_id", type="integer"),
 *     @OA\Property(property="atendente_id", type="integer", nullable=true),
 *     @OA\Property(property="data_inicio", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="data_fim", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="observacoes", type="string", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", readOnly=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", readOnly=true),
 *     @OA\Property(property="setor", ref="#/components/schemas/Setor"),
 *     @OA\Property(property="solicitante", ref="#/components/schemas/User"),
 *     @OA\Property(property="atendente", ref="#/components/schemas/Atendente"),
 *     @OA\Property(property="historico", type="array", @OA\Items(ref="#/components/schemas/HistoricoChamado"))
 * )
 */
class Chamado extends Model
{
    use HasFactory;

    protected $table = 'chamados';

    protected $fillable = [
        'titulo',
        'descricao',
        'status',
        'prioridade',
        'setor_id',
        'solicitante_id',
        'atendente_id',
        'data_inicio',
        'data_fim',
        'observacoes'
    ];

    protected $casts = [
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
    ];

    // Relacionamentos
    public function setor()
    {
        return $this->belongsTo(Setor::class);
    }

    public function solicitante()
    {
        return $this->belongsTo(User::class, 'solicitante_id');
    }

    public function atendente()
    {
        return $this->belongsTo(Atendente::class);
    }

    public function historico()
    {
        return $this->hasMany(HistoricoChamado::class);
    }

    // Status possíveis do chamado
    const STATUS_ABERTO = 'aberto';
    const STATUS_EM_ANDAMENTO = 'em_andamento';
    const STATUS_TRANSFERIDO = 'transferido';
    const STATUS_RESOLVIDO = 'resolvido';
    const STATUS_FECHADO = 'fechado';

    // Prioridades possíveis
    const PRIORIDADE_BAIXA = 'baixa';
    const PRIORIDADE_MEDIA = 'media';
    const PRIORIDADE_ALTA = 'alta';
    const PRIORIDADE_URGENTE = 'urgente';
}
