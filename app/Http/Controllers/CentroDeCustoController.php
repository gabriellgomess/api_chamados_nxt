<?php

namespace App\Http\Controllers;

use App\Models\CentroDeCusto;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Centros de Custo",
 *     description="Operações relacionadas aos centros de custo"
 * )
 */
class CentroDeCustoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/centros_de_custo",
     *     tags={"Centros de Custo"},
     *     summary="Lista todos os centros de custo",
     *      security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de centros de custo",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/CentroDeCusto"))
     *     )
     * )
     */
    public function index()
    {
        $user = Auth::user();

        return CentroDeCusto::all();
    }

    /**
     * @OA\Get(
     *     path="/api/centros_de_custo/{id}",
     *     tags={"Centros de Custo"},
     *     summary="Mostra um centro de custo específico",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do centro de custo",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do centro de custo",
     *         @OA\JsonContent(ref="#/components/schemas/CentroDeCusto")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Centro de custo não encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        return CentroDeCusto::findOrFail($id);
    }

    /**
     * @OA\Post(
     *     path="/api/centros_de_custo",
     *     tags={"Centros de Custo"},
     *     summary="Cria um novo centro de custo",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome", "descricao", "codigo"},
     *             @OA\Property(property="nome", type="string", example="Nome do Centro de Custo"),
     *             @OA\Property(property="descricao", type="string", example="Descrição do Centro de Custo"),
     *             @OA\Property(property="codigo", type="string", example="001")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Centro de custo criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/CentroDeCusto")
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
            'descricao' => 'required|string',
            'codigo' => 'required|string',
        ]);

        $centroDeCusto = CentroDeCusto::create($validated);

        return response()->json($centroDeCusto, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/centros_de_custo/{id}",
     *     tags={"Centros de Custo"},
     *     summary="Atualiza um centro de custo existente",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do centro de custo",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome", "descricao", "codigo"},
     *             @OA\Property(property="nome", type="string", example="Nome do Centro de Custo"),
     *             @OA\Property(property="descricao", type="string", example="Descrição do Centro de Custo"),
     *             @OA\Property(property="codigo", type="string", example="001")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Centro de custo atualizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/CentroDeCusto")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Centro de custo não encontrado"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Dados inválidos"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $centroDeCusto = CentroDeCusto::findOrFail($id);

        $validated = $request->validate([
            'nome' => 'required|string',
            'descricao' => 'required|string',
            'codigo' => 'required|string',
        ]);

        $centroDeCusto->update($validated);

        return response()->json($centroDeCusto, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/centros_de_custo/{id}",
     *     tags={"Centros de Custo"},
     *     summary="Deleta um centro de custo",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do centro de custo",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Centro de custo deletado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Centro de custo não encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $centroDeCusto = CentroDeCusto::findOrFail($id);
        $centroDeCusto->delete();

        return response()->noContent();
    }
}
