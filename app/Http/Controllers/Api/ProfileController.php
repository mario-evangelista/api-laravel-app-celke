<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\UserPasswordRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{

    /**
     * Exibe o perfil do usuário autenticado.
     *
     * Este método recupera os dados do usuário autenticado e retorna o perfil em formato JSON.
     * Ele também registra a visualização do perfil no log.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(): JsonResponse
    {

        try {
            // Recuperar os dados do usuário logado
            $user = User::where('id', Auth::id())->first();

            // Acessar o IF quando não encontrar o usuário
            if (!$user) {

                // Salvar log
                Log::notice('Perfil não encontrado.');

                return response()->json([
                    'status' => false,
                    'message' => 'Perfil não encontrado!'
                ], 400);
            }

            // Salvar log
            Log::info('Visualizar perfil.', ['user_id' => $user->id, 'action_user_id' => Auth::id()]);

            return response()->json([
                'status' => false,
                'user' => $user
            ], 200);
        } catch (Exception $e) {

            // Salvar log
            Log::notice('Perfil não encontrado.', ['action_user_id' => Auth::id(), 'error' => $e->getMessage()]);

            // Retorna uma mensagem de erro com status 400
            return response()->json([
                'status' => true,
                'message' => 'Perfil não encontrado!'
            ], 400);
        }
    }

    /**
     * Atualiza o perfil do usuário autenticado.
     *
     * Este método atualiza as informações do perfil do usuário autenticado com os dados fornecidos na solicitação
     * e retorna o perfil atualizado em formato JSON.
     *
     * @param  \App\Http\Requests\ProfileRequest  $request Os dados do perfil a serem atualizados
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProfileRequest $request): JsonResponse
    {

        // Iniciar a transação
        DB::beginTransaction();

        try {
            // Recuperar os dados do usuário logado
            $user = User::where('id', Auth::id())->first();

            // Acessar o IF quando não encontrar o usuário
            if (!$user) {

                // Salvar log
                Log::notice('Perfil não encontrado.');

                return response()->json([
                    'status' => false,
                    'message' => 'Perfil não encontrado!'
                ], 400);
            }

            // Editar as informações do registro no banco de dados
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // Operação é concluída com êxito
            DB::commit();

            // Salvar log
            Log::info('Perfil editado.', ['user_id' => $user->id, 'action_user_id' => Auth::id()]);

            // Retornar os dados em formato de objeto e status 200
            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => 'Perfil editado com sucesso!',
            ], 200);
        } catch (Exception $e) {

            // Operação não é concluída com êxito
            DB::rollBack();

            // Salvar log
            Log::notice('Perfil não editado.', ['action_user_id' => Auth::id(), 'error' => $e->getMessage()]);

            // Retorna uma mensagem de erro com status 400
            return response()->json([
                'status' => true,
                'message' => 'Perfil não editado!'
            ], 400);
        }
    }

    /**
     * Atualiza a senha do perfil do usuário autenticado.
     *
     * Este método atualiza a senha do perfil do usuário autenticado com a nova senha fornecida na solicitação
     * e retorna o perfil atualizado em formato JSON.
     *
     * @param  \App\Http\Requests\UserPasswordRequest  $request Os dados da nova senha
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(UserPasswordRequest $request): JsonResponse
    {

        // Iniciar a transação
        DB::beginTransaction();

        try {
            // Recuperar os dados do usuário logado
            $user = User::where('id', Auth::id())->first();

            // Acessar o IF quando não encontrar o usuário
            if (!$user) {

                // Salvar log
                Log::notice('Perfil não encontrado.');

                return response()->json([
                    'status' => false,
                    'message' => 'Perfil não encontrado!'
                ], 400);
            }

            // Editar as informações do registro no banco de dados
            $user->update([
                'password' => $request->password,
            ]);

            // Operação é concluída com êxito
            DB::commit();

            // Salvar log
            Log::info('Senha do perfil editado.', ['user_id' => $user->id, 'action_user_id' => Auth::id()]);

            // Retornar os dados em formato de objeto e status 200
            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => 'Senha do perfil editado com sucesso!',
            ], 200);
        } catch (Exception $e) {

            // Operação não é concluída com êxito
            DB::rollBack();

            // Salvar log
            Log::notice('Senha do perfil não editado.', ['action_user_id' => Auth::id(), 'error' => $e->getMessage()]);

            // Retorna uma mensagem de erro com status 400
            return response()->json([
                'status' => true,
                'message' => 'Senha do perfil não editado!'
            ], 400);
        }
    }
}
