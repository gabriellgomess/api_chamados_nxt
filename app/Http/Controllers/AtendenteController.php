<?php

namespace App\Http\Controllers;

use App\Models\Atendente;
use App\Models\SetorAtendente;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Atendentes",
 *     description="Operações relacionadas aos atendentes"
 * )
 */
class AtendenteController extends Controller
{
/**
 * @OA\Get(
 *     path="/api/atendentes",
 *     tags={"Atendentes"},
 *     summary="Lista todos os atendentes",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de atendentes",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Atendente"))
 *     )
 * )
 */
    public function index()
    {
        return Atendente::all();
    }

    /**
     * @OA\Get(
     *     path="/api/atendentes/{id}",
     *     tags={"Atendentes"},
     *     summary="Mostra um atendente específico",
     * security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do atendente",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do atendente",
     *         @OA\JsonContent(ref="#/components/schemas/Atendente")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Atendente não encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        return Atendente::find($id);
    }

    /**
     * @OA\Post(
     *     path="/api/atendentes",
     *     tags={"Atendentes"},
     *     summary="Cria um novo atendente",
     * security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome", "email"},
     *             @OA\Property(property="nome", type="string", example="Nome do Atendente"),
     *             @OA\Property(property="email", type="string", format="email", example="atendente@example.com"),
     *             @OA\Property(property="telefone", type="string", example="123456789"),
     *             @OA\Property(property="setor_id", type="integer", example=1),
     *             @OA\Property(property="is_gestor", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Atendente criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Atendente")
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
            'email' => 'required|string|email',
            'telefone' => 'nullable|string',
        ]);

        $atendente = Atendente::create($validated);

        if ($request->filled('setor_id')) {
            SetorAtendente::create([
                'setor_id' => $request->setor_id,
                'atendente_id' => $atendente->id,
                'is_gestor' => $request->is_gestor ?? false,
            ]);
        }

        return response()->json($atendente, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/atendentes/{id}",
     *     tags={"Atendentes"},
     *     summary="Atualiza um atendente existente",
     * security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do atendente",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome", "email"},
     *             @OA\Property(property="nome", type="string", example="Nome do Atendente"),
     *             @OA\Property(property="email", type="string", format="email", example="atendente@example.com"),
     *             @OA\Property(property="telefone", type="string", example="123456789"),
     *             @OA\Property(property="setor_id", type="integer", example=1),
     *             @OA\Property(property="is_gestor", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Atendente atualizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Atendente")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Atendente não encontrado"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Dados inválidos"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $atendente = Atendente::findOrFail($id);

        $validated = $request->validate([
            'nome' => 'required|string',
            'email' => 'required|string|email',
            'telefone' => 'nullable|string',
        ]);

        $atendente->update($validated);

        if ($request->filled('setor_id')) {
            $setorAtendente = SetorAtendente::where('atendente_id', $id)->first();
            if ($setorAtendente) {
                $setorAtendente->update([
                    'setor_id' => $request->setor_id,
                    'is_gestor' => $request->is_gestor ?? false,
                ]);
            } else {
                SetorAtendente::create([
                    'setor_id' => $request->setor_id,
                    'atendente_id' => $id,
                    'is_gestor' => $request->is_gestor ?? false,
                ]);
            }
        }

        return response()->json($atendente, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/atendentes/{id}",
     *     tags={"Atendentes"},
     *     summary="Deleta um atendente",
     * security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do atendente",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Atendente deletado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Atendente não encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $atendente = Atendente::findOrFail($id);
        $atendente->delete();

        return response()->noContent();
    }
}
