<?php

use App\Http\Controllers\Api\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CentroDeCustoController;
use App\Http\Controllers\SetorController;
use App\Http\Controllers\AtendenteController;
use App\Http\Controllers\SetorAtendenteController;
use App\Http\Controllers\ChamadoController;

// Rota pública
//Swagger
Route::get('/', function () {
    return redirect('/api/documentation');
});
// POST - http://127.0.0.1:8000/api/ - { "email": "cesar@celke.com.br", "password": "123456a" }
Route::post('/login', [LoginController::class, 'login'])->name('login');

// POST - http://127.0.0.1:8000/api/register - { "name": "Gabriel Gomes", "email": "gabriel.gomes@outlook.com", "password: "10203040", "password_confirmation": "10203040"}
Route::post('/register', [LoginController::class, 'register']);

// Rota restrita
Route::group(['middleware' => ['auth:sanctum']], function () {

    // POST - http://127.0.0.1:8000/api/logout/1
    Route::post('/logout/{user}', [LoginController::class, 'logout']);

    Route::get('/usuarios', [UserController::class, 'index']);


    Route::apiResource('/centros_de_custo', CentroDeCustoController::class);
    Route::apiResource('/setores', SetorController::class);
    Route::apiResource('/atendentes', AtendenteController::class);
    Route::apiResource('/setores_atendentes', SetorAtendenteController::class);

    // Rotas de Chamados
    Route::apiResource('chamados', ChamadoController::class);
    Route::post('chamados/{chamado}/transferir', [ChamadoController::class, 'transferir']);
    Route::post('chamados/{chamado}/resolver', [ChamadoController::class, 'resolver']);
    Route::get('chamados/{chamado}/historico', [ChamadoController::class, 'historico']);
});
