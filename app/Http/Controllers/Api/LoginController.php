<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{

    /**
     * Realiza a autenticação do usuário.
     *
     * Este método tenta autenticar o usuário com as credenciais fornecidas.
     * Se a autenticação for bem-sucedida, retorna o usuário autenticado juntamente com um token de acesso.
     * Se a autenticação falhar, retorna uma mensagem de erro.
     *
     * @param \Illuminate\Http\Request $request O objeto de requisição HTTP contendo as credenciais do usuário (email e senha).
     * @return \Illuminate\Http\JsonResponse Uma resposta JSON contendo o usuário autenticado e um token de acesso se a autenticação for bem-sucedida, ou uma mensagem de erro se a autenticação falhar.
     */
    public function login(Request $request): JsonResponse
    {
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

                // Recuperar os dados do usuário
                $user = Auth::user();

                // Gerar o token para o usuário logado
                $token = $request->user()->createToken('api-token')->plainTextToken;

                // Salvar log
                Log::info('Login realizado com sucesso.', ['action_user_id' => Auth::id()]);

                return response()->json([
                    'status' => true,
                    'token' => $token,
                    'user' => $user,
                ], 201);
            } else {

                // Salvar log
                Log::notice('Login ou senha incorreta.', ['email' => $request->email]);

                return response()->json([
                    'status' => false,
                    'message' => 'Login ou senha incorreta.'
                ], 404);
            }
        } catch (Exception $e) {

            // Salvar log
            Log::warning('Erro ao validar os dados.', ['email' => $request->email, 'error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'message' => 'Login ou senha incorreta.'
            ], 404);
        }
    }

    /**
     * Realiza o logout do usuário.
     *
     * Este método revoga todos os tokens de acesso associados ao usuário, efetuando assim o logout.
     * Se o logout for bem-sucedido, retorna uma resposta JSON indicando sucesso.
     * Se ocorrer algum erro durante o logout, retorna uma resposta JSON indicando falha.
     *
     * @return \Illuminate\Http\JsonResponse Uma resposta JSON indicando o status do logout e uma mensagem correspondente.
     */
    public function logout(): JsonResponse
    {
        try {

            // Verificar se o usuário está logado
            $authUserId = Auth::check() ? Auth::id() : '';

            // Retorna a mensagem de erro quando o usuário não estiver logado
            if (!$authUserId) {

                // Salvar log
                Log::notice('Usuário não está logado.');

                return response()->json([
                    'status' => false,
                    'message' => 'Usuário não está logado.'
                ], 400);
            }

            // Recuperar os dados do usuário logado
            $user = User::where('id', $authUserId)->first();

            // Excluir os tokens do usuário
            $user->tokens()->delete();

            // Salvar log
            Log::info('Deslogado com sucesso', ['action_user_id' => $authUserId]);

            return response()->json([
                'status' => true,
                'message' => 'Deslogado com sucesso.'
            ], 200);
        } catch (Exception $e) {

            // Verificar se o usuário está logado
            $authUserId = Auth::check() ? Auth::id() : '';

            // Salvar log
            Log::notice('Logout não realizado.', ['action_user_id' => $authUserId, 'error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'message' => 'Não deslogado.'
            ], 400);
        }
    }
}