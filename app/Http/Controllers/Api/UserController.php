<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserPasswordRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Retorna uma lista paginada de usuários.
     *
     * Este método recupera uma lista paginada de usuários do banco de dados
     * e a retorna como uma resposta JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        // Recupera os usuários do banco de dados, ordenados pelo id em ordem decrescente, paginados
        $users = User::orderBy('id', 'DESC')->paginate(40);

        // Salvar log
        Log::info('Listar os usuários.', ['action_user_id' => Auth::id()]);

        // Retorna os usuários recuperados como uma resposta JSON
        return response()->json([
            'status' => true,
            'users' => $users,
        ], 200);
    }

    /**
     * Exibe os detalhes de um usuário específico.
     *
     * Este método retorna os detalhes de um usuário específico em formato JSON.
     *
     * @param  \App\Models\User  $user O objeto do usuário a ser exibido
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user): JsonResponse
    {

        // Salvar log
        Log::info('Visualizar o usuário.', ['user_id' => $user->id, 'action_user_id' => Auth::id()]);

        // Retorna os detalhes do usuário em formato JSON
        return response()->json([
            'status' => true,
            'user' => $user,
        ], 200);
    }

    /**
     * Cria novo usuário com os dados fornecidos na requisição.
     *
     * @param  \App\Http\Requests\UserRequest  $request O objeto de requisição contendo os dados do usuário a ser criado.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserRequest $request): JsonResponse
    {

        // Iniciar a transação
        DB::beginTransaction();

        try {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            // Operação é concluída com êxito
            DB::commit();

            // Salvar log
            Log::info('Usuário cadastrado.', ['user_id' => $user->id, 'action_user_id' => Auth::id()]);

            // Retorna os dados do usuário criado e uma mensagem de sucesso com status 201
            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => 'Usuário cadastrado com sucesso!',
            ], 201);
        } catch (Exception $e) {

            // Operação não é concluída com êxito
            DB::rollBack();

            // Salvar log
            Log::notice('Usuário não cadastrado.', ['action_user_id' => Auth::id(), 'error' => $e->getMessage()]);

            // Retorna uma mensagem de erro com status 400
            return response()->json([
                'status' => false,
                'message' => 'Usuário não cadastrado!',
                'exception' => $e,
            ], 400);
        }
    }

    /**
     * Atualizar os dados de um usuário existente com base nos dados fornecidos na requisição.
     *
     * @param  \App\Http\Requests\UserRequest  $request O objeto de requisição contendo os dados do usuário a ser atualizado.
     * @param  \App\Models\User  $user O usuário a ser atualizado.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserRequest $request, User $user): JsonResponse
    {

        // Iniciar a transação
        DB::beginTransaction();

        try {

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // Operação é concluída com êxito
            DB::commit();

            // Salvar log
            Log::info('Usuário editado.', ['user_id' => $user->id, 'action_user_id' => Auth::id()]);

            // Retornar os dados em formato de objeto e status 200
            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => 'Usuário editado com sucesso!',
            ], 200);
        } catch (Exception $e) {

            // Operação não é concluída com êxito
            DB::rollBack();

            // Salvar log
            Log::notice('Usuário não editado.', ['action_user_id' => Auth::id(), 'error' => $e->getMessage()]);

            // Retornar os dados em formato de objeto e status 400
            return response()->json([
                'status' => false,
                'message' => 'Usuário não editado!',
            ], 400);
        }
    }

    /**
     * Atualiza a senha de um usuário.
     *
     * Este método atualiza a senha do usuário fornecido com a nova senha fornecida na solicitação
     * e retorna o usuário atualizado em formato JSON.
     *
     * @param  \App\Http\Requests\UserPasswordRequest  $request Os dados da nova senha
     * @param  \App\Models\User  $user O usuário cuja senha será atualizada
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(UserPasswordRequest $request, User $user): JsonResponse
    {

        // Iniciar a transação
        DB::beginTransaction();

        try {

            $user->update([
                'password' => $request->password,
            ]);

            // Operação é concluída com êxito
            DB::commit();

            // Salvar log
            Log::info('Senha do usuário editado.', ['user_id' => $user->id, 'action_user_id' => Auth::id()]);

            // Retornar os dados em formato de objeto e status 200
            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => 'Senha do usuário editado com sucesso!',
            ], 200);
        } catch (Exception $e) {

            // Operação não é concluída com êxito
            DB::rollBack();

            // Salvar log
            Log::notice('Senha do usuário não editado.', ['action_user_id' => Auth::id(), 'error' => $e->getMessage()]);

            // Retornar os dados em formato de objeto e status 400
            return response()->json([
                'status' => false,
                'message' => 'Senha do usuário não editado!',
            ], 400);
        }
    }

    /**
     * Excluir usuário no banco de dados.
     *
     * @param  \App\Models\User  $user O usuário a ser excluído.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        try {

            // Excluir o registro do banco de dados
            $user->delete();

            // Salvar log
            Log::info('Usuário apagado.', ['user_id' => $user->id, 'action_user_id' => Auth::id()]);

            // Retornar os dados em formato de objeto e status 200
            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => 'Usuário apagado com sucesso!',
            ], 200);
        } catch (Exception $e) {

            // Salvar log
            Log::notice('Usuário não apagado.', ['action_user_id' => Auth::id(), 'error' => $e->getMessage()]);

            // Retornar os dados em formato de objeto e status 400
            return response()->json([
                'status' => false,
                'message' => 'Usuário não apagado!',
            ], 400);
        }
    }
}