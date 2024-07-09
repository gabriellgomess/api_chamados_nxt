<?php

namespace App\Http\Controllers;

use App\Models\SetorAtendente;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Setores Atendentes",
 *     description="Operações relacionadas aos setores atendentes"
 * )
 */
class SetorAtendenteController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/setores_atendentes",
     *     tags={"Setores Atendentes"},
     *     summary="Lista todos os setores atendentes",
     * security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de setores atendentes",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/SetorAtendente"))
     *     )
     * )
     */
    public function index()
    {
        return SetorAtendente::with('setor', 'atendente')->get();
    }

    /**
     * @OA\Get(
     *     path="/api/setores_atendentes/{id}",
     *     tags={"Setores Atendentes"},
     *     summary="Mostra um setor atendente específico",
     * security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do setor atendente",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do setor atendente",
     *         @OA\JsonContent(ref="#/components/schemas/SetorAtendente")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Setor atendente não encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        return SetorAtendente::with('setor', 'atendente')->find($id);
    }

    /**
     * @OA\Post(
     *     path="/api/setores_atendentes",
     *     tags={"Setores Atendentes"},
     *     summary="Cria um novo setor atendente",
     * security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"setor_id", "atendente_id", "is_gestor"},
     *             @OA\Property(property="setor_id", type="integer", example=1),
     *             @OA\Property(property="atendente_id", type="integer", example=1),
     *             @OA\Property(property="is_gestor", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Setor atendente criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/SetorAtendente")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Dados inválidos"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'setor_id' => 'required|exists:setores,id',
            'atendente_id' => 'required|exists:atendentes,id',
            'is_gestor' => 'required|boolean',
        ]);

        return response()->json(SetorAtendente::create($validated), 201);
    }

    /**
     * @OA\Put(
     *     path="/api/setores_atendentes/{id}",
     *     tags={"Setores Atendentes"},
     *     summary="Atualiza um setor atendente existente",
     * security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do setor atendente",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"setor_id", "atendente_id", "is_gestor"},
     *             @OA\Property(property="setor_id", type="integer", example=1),
     *             @OA\Property(property="atendente_id", type="integer", example=1),
     *             @OA\Property(property="is_gestor", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Setor atendente atualizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/SetorAtendente")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Setor atendente não encontrado"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Dados inválidos"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $setorAtendente = SetorAtendente::findOrFail($id);

        $validated = $request->validate([
            'setor_id' => 'required|exists:setores,id',
            'atendente_id' => 'required|exists:atendentes,id',
            'is_gestor' => 'required|boolean',
        ]);

        $setorAtendente->update($validated);

        return response()->json($setorAtendente, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/setores_atendentes/{id}",
     *     tags={"Setores Atendentes"},
     *     summary="Deleta um setor atendente",
     * security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do setor atendente",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Setor atendente deletado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Setor atendente não encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $setorAtendente = SetorAtendente::findOrFail($id);
        $setorAtendente->delete();

        return response()->noContent();
    }
}
