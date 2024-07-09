<?php

namespace App\Http\Controllers;

use App\Models\Setor;
use Illuminate\Http\Request;


/**
 * @OA\Tag(
 *     name="Setores",
 *     description="API Endpoints de Setores"
 * )
 */
class SetorController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/setores",
     *     tags={"Setores"},
     *     summary="Lista todos os setores",
     * security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de setores"
     *     )
     * )
     */
    public function index()
    {
        return Setor::with('centroDeCusto')->get();
    }

    /**
     * @OA\Get(
     *     path="/api/setores/{id}",
     *     tags={"Setores"},
     *     summary="Mostra um setor específico",
     *      security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do setor",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do setor"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Setor não encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        return Setor::with('centroDeCusto')->findOrFail($id);
    }

    /**
     * @OA\Post(
     *     path="/api/setores",
     *     tags={"Setores"},
     *     summary="Cria um novo setor",
     *  security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome", "centro_de_custo_id"},
     *             @OA\Property(property="nome", type="string", example="Nome do Setor"),
     *             @OA\Property(property="descricao", type="string", example="Descrição do Setor"),
     *             @OA\Property(property="codigo", type="string", example="001"),
     *             @OA\Property(property="centro_de_custo_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Setor criado com sucesso"
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
            'nome' => 'required|string',
            'descricao' => 'nullable|string',
            'codigo' => 'nullable|string',
            'centro_de_custo_id' => 'required|exists:centros_de_custo,id',
        ]);

        $setor = Setor::create($validated);

        return response()->json($setor, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/setores/{id}",
     *     tags={"Setores"},
     *     summary="Atualiza um setor existente",
     *  security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do setor",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome", "centro_de_custo_id"},
     *             @OA\Property(property="nome", type="string", example="Nome do Setor"),
     *             @OA\Property(property="descricao", type="string", example="Descrição do Setor"),
     *             @OA\Property(property="codigo", type="string", example="001"),
     *             @OA\Property(property="centro_de_custo_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Setor atualizado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Setor não encontrado"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Dados inválidos"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $setor = Setor::findOrFail($id);

        // \Log::info('Dados recebidos no request:', $request->all());

        $validated = $request->validate([
            'nome' => 'required|string',
            'descricao' => 'nullable|string',
            'codigo' => 'nullable|string',
            'centro_de_custo_id' => 'required|exists:centros_de_custo,id',
        ]);

        $setor->update($validated);

        return response()->json($setor, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/setores/{id}",
     *     tags={"Setores"},
     *     summary="Deleta um setor",
     *  security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do setor",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Setor deletado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Setor não encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $setor = Setor::findOrFail($id);
        $setor->delete();

        return response()->noContent();
    }
}
