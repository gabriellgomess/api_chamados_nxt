<?php

namespace App\Http\Controllers;

use App\Models\Chamado;
use App\Models\HistoricoChamado;
use App\Models\Setor;
use App\Models\Atendente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Chamados",
 *     description="API Endpoints para gerenciamento de chamados"
 * )
 */
class ChamadoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/chamados",
     *     summary="Lista todos os chamados",
     *     description="Retorna uma lista paginada de chamados com opções de filtro",
     *     operationId="indexChamados",
     *     tags={"Chamados"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filtrar por status do chamado",
     *         required=false,
     *         @OA\Schema(type="string", enum={"aberto", "em_andamento", "transferido", "resolvido", "fechado"})
     *     ),
     *     @OA\Parameter(
     *         name="setor_id",
     *         in="query",
     *         description="Filtrar por setor",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="prioridade",
     *         in="query",
     *         description="Filtrar por prioridade",
     *         required=false,
     *         @OA\Schema(type="string", enum={"baixa", "media", "alta", "urgente"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de chamados retornada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Chamado")),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Chamado::with(['setor', 'solicitante', 'atendente']);

        // Filtros
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('setor_id')) {
            $query->where('setor_id', $request->setor_id);
        }

        if ($request->has('prioridade')) {
            $query->where('prioridade', $request->prioridade);
        }

        // Se for atendente, mostra apenas chamados do seu setor
        if (Auth::user()->atendente) {
            $query->where('setor_id', Auth::user()->atendente->setor_id);
        }

        return response()->json($query->paginate(10));
    }

    /**
     * @OA\Post(
     *     path="/api/chamados",
     *     summary="Cria um novo chamado",
     *     description="Cria um novo chamado no sistema",
     *     operationId="storeChamado",
     *     tags={"Chamados"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"titulo", "descricao", "setor_id", "prioridade"},
     *             @OA\Property(property="titulo", type="string", maxLength=255),
     *             @OA\Property(property="descricao", type="string"),
     *             @OA\Property(property="setor_id", type="integer"),
     *             @OA\Property(property="prioridade", type="string", enum={"baixa", "media", "alta", "urgente"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Chamado criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Chamado")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'setor_id' => 'required|exists:setores,id',
            'prioridade' => 'required|in:baixa,media,alta,urgente'
        ]);

        $chamado = Chamado::create([
            ...$validated,
            'solicitante_id' => Auth::id(),
            'status' => Chamado::STATUS_ABERTO
        ]);

        // Registrar histórico
        HistoricoChamado::create([
            'chamado_id' => $chamado->id,
            'usuario_id' => Auth::id(),
            'acao' => HistoricoChamado::ACAO_CRIACAO,
            'descricao' => 'Chamado criado'
        ]);

        return response()->json($chamado->load(['setor', 'solicitante']), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/chamados/{id}",
     *     summary="Obtém detalhes de um chamado",
     *     description="Retorna os detalhes completos de um chamado específico",
     *     operationId="showChamado",
     *     tags={"Chamados"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do chamado",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do chamado retornados com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Chamado")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Chamado não encontrado"
     *     )
     * )
     */
    public function show(Chamado $chamado)
    {
        return response()->json($chamado->load(['setor', 'solicitante', 'atendente', 'historico']));
    }

    /**
     * @OA\Put(
     *     path="/api/chamados/{id}",
     *     summary="Atualiza um chamado",
     *     description="Atualiza os dados de um chamado existente",
     *     operationId="updateChamado",
     *     tags={"Chamados"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do chamado",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", enum={"aberto", "em_andamento", "transferido", "resolvido", "fechado"}),
     *             @OA\Property(property="prioridade", type="string", enum={"baixa", "media", "alta", "urgente"}),
     *             @OA\Property(property="observacoes", type="string"),
     *             @OA\Property(property="setor_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chamado atualizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Chamado")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Chamado não encontrado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação"
     *     )
     * )
     */
    public function update(Request $request, Chamado $chamado)
    {
        $validated = $request->validate([
            'status' => 'sometimes|in:aberto,em_andamento,transferido,resolvido,fechado',
            'prioridade' => 'sometimes|in:baixa,media,alta,urgente',
            'observacoes' => 'sometimes|string',
            'setor_id' => 'sometimes|exists:setores,id'
        ]);

        $dadosAnteriores = $chamado->toArray();
        $chamado->update($validated);
        $dadosNovos = $chamado->fresh()->toArray();

        // Registrar histórico
        HistoricoChamado::create([
            'chamado_id' => $chamado->id,
            'usuario_id' => Auth::id(),
            'acao' => HistoricoChamado::ACAO_ATUALIZACAO,
            'descricao' => 'Chamado atualizado',
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos
        ]);

        return response()->json($chamado->load(['setor', 'solicitante', 'atendente']));
    }

    /**
     * @OA\Post(
     *     path="/api/chamados/{id}/transferir",
     *     summary="Transfere um chamado para outro setor",
     *     description="Transfere um chamado para um setor diferente",
     *     operationId="transferirChamado",
     *     tags={"Chamados"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do chamado",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"setor_id", "observacoes"},
     *             @OA\Property(property="setor_id", type="integer"),
     *             @OA\Property(property="observacoes", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chamado transferido com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Chamado")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Chamado não encontrado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação"
     *     )
     * )
     */
    public function transferir(Request $request, Chamado $chamado)
    {
        $validated = $request->validate([
            'setor_id' => 'required|exists:setores,id',
            'observacoes' => 'required|string'
        ]);

        $dadosAnteriores = $chamado->toArray();
        $chamado->update([
            'setor_id' => $validated['setor_id'],
            'status' => Chamado::STATUS_TRANSFERIDO,
            'observacoes' => $validated['observacoes']
        ]);
        $dadosNovos = $chamado->fresh()->toArray();

        // Registrar histórico
        HistoricoChamado::create([
            'chamado_id' => $chamado->id,
            'usuario_id' => Auth::id(),
            'acao' => HistoricoChamado::ACAO_TRANSFERENCIA,
            'descricao' => 'Chamado transferido para outro setor',
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos
        ]);

        return response()->json($chamado->load(['setor', 'solicitante', 'atendente']));
    }

    /**
     * @OA\Post(
     *     path="/api/chamados/{id}/resolver",
     *     summary="Resolve um chamado",
     *     description="Marca um chamado como resolvido",
     *     operationId="resolverChamado",
     *     tags={"Chamados"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do chamado",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"observacoes"},
     *             @OA\Property(property="observacoes", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chamado resolvido com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Chamado")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Chamado não encontrado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação"
     *     )
     * )
     */
    public function resolver(Request $request, Chamado $chamado)
    {
        $validated = $request->validate([
            'observacoes' => 'required|string'
        ]);

        $dadosAnteriores = $chamado->toArray();
        $chamado->update([
            'status' => Chamado::STATUS_RESOLVIDO,
            'data_fim' => now(),
            'observacoes' => $validated['observacoes']
        ]);
        $dadosNovos = $chamado->fresh()->toArray();

        // Registrar histórico
        HistoricoChamado::create([
            'chamado_id' => $chamado->id,
            'usuario_id' => Auth::id(),
            'acao' => HistoricoChamado::ACAO_RESOLUCAO,
            'descricao' => 'Chamado resolvido',
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos
        ]);

        return response()->json($chamado->load(['setor', 'solicitante', 'atendente']));
    }

    /**
     * @OA\Get(
     *     path="/api/chamados/{id}/historico",
     *     summary="Obtém o histórico de um chamado",
     *     description="Retorna o histórico completo de alterações de um chamado específico",
     *     operationId="historicoChamado",
     *     tags={"Chamados"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do chamado",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Histórico do chamado retornado com sucesso",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/HistoricoChamado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Chamado não encontrado"
     *     )
     * )
     */
    public function historico(Chamado $chamado)
    {
        return response()->json($chamado->historico()->with('usuario')->orderBy('created_at', 'desc')->get());
    }
}
