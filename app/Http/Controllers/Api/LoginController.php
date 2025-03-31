<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Realiza a autenticação do usuário",
     *     description="Este método tenta autenticar o usuário com as credenciais fornecidas. Se a autenticação for bem-sucedida, retorna o usuário autenticado juntamente com um token de acesso. Se a autenticação falhar, retorna uma mensagem de erro.",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário autenticado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="token", type="string", example="token-de-acesso"),
     *             @OA\Property(property="user", type="object", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Login ou senha incorreta",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Login ou senha incorreta.")
     *         )
     *     )
     * )
     */
    public function login(Request $request): JsonResponse
    {
        // Validar o e-mail e a senha
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            // Recuperar os dados do usuário
            $user = Auth::user();

            $token = $request->user()->createToken('api-token')->plainTextToken;

            return response()->json([
                'status' => true,
                'token' => $token,
                'user' => $user,
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Login ou senha incorreta.',
            ], 404);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Registrar um novo usuário",
     *      security={{"bearerAuth":{}}},
     *     description="Este método registra um novo usuário no sistema.",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation"},
     *             @OA\Property(property="name", type="string", example="Gabriel Gomes"),
     *             @OA\Property(property="email", type="string", format="email", example="gabriel.gomes@outlook.com"),
     *            @OA\Property(property="telefone", type="string", example="51997073430"),
     *             @OA\Property(property="password", type="string", format="password", example="10203040"),
     *             @OA\Property(property="level_access", type="interger", format="number", example="0"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="10203040")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário registrado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="user", type="object", ref="#/components/schemas/User")
     *         )
     *     )
     * )
     */
    public function register(Request $request): JsonResponse
    {
        // Validar os dados do usuário
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'level_access' => 'required|min:0|max:3',
            'telefone' => 'nullable|string',
        ]);

        // Criar um novo usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'telefone' => $request->telefone, // Adiciona o telefone
            'password' => Hash::make($request->password),
            'level_access' => $request->level_access,
        ]);

        return response()->json([
            'status' => true,
            'user' => $user,
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/logout/{user}",
     *     summary="Realiza o logout do usuário",
     *     security={{"bearerAuth":{}}},
     *     description="Este método revoga todos os tokens de acesso associados ao usuário, efetuando assim o logout.",
     *     tags={"Auth"},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Deslogado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Deslogado com sucesso.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao deslogar",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Não deslogado.")
     *         )
     *     )
     * )
     */
    public function logout(User $user): JsonResponse
    {
        try {

            $user->tokens()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Deslogado com sucesso.',
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Não deslogado.',
            ], 400);
        }
    }
}
