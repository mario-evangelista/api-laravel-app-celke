<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BillRequest;
use App\Models\Bill;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BillController extends Controller
{

    /**
     * Retorna uma lista paginada de contas.
     *
     * Este método recupera uma lista paginada de contas do banco de dados
     * e a retorna como uma resposta JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {

        // Recupera as contas do banco de dados, ordenadas pelo id em ordem decrescente, paginadas
        $bills = Bill::orderBy('id', 'DESC')->paginate(40);

        // Salvar log
        Log::info('Listar as contas.', ['action_user_id' => Auth::id()]);

        // Retorna as contas recuperadas como uma resposta JSON
        return response()->json([
            'status' => true,
            'bills' => $bills,
        ], 200);
    }

    /**
     * Exibe os detalhes de uma conta específica.
     *
     * Este método retorna os detalhes de uma conta específica em formato JSON.
     *
     * @param  \App\Models\Bill  $bill O objeto da conta a ser exibido
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Bill $bill): JsonResponse
    {

        // Salvar log
        Log::info('Visualizar a conta.', ['bill_id' => $bill->id, 'action_user_id' => Auth::id()]);

        // Retorna os detalhes da conta em formato JSON
        return response()->json([
            'status' => true,
            'bill' => $bill,
        ], 200);
    }

    /**
     * Cria uma nova conta.
     *
     * Este método cria uma nova conta com os dados fornecidos na solicitação
     * e retorna a conta recém-criada em formato JSON.
     *
     * @param  \App\Http\Requests\BillRequest $request Os dados da nova conta
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BillRequest $request): JsonResponse
    {

        // Iniciar a transação
        DB::beginTransaction();

        try {

            // Cadastrar o registro no banco de dados
            $bill = Bill::create([
                'name' => $request->name,
                'bill_value' => $request->bill_value,
                'due_date' => $request->due_date
            ]);

            // Operação é concluída com êxito
            DB::commit();

            // Salvar log
            Log::info('Conta cadastrada.', ['bill_id' => $bill->id, 'action_user_id' => Auth::id()]);

            // Retorna os dados da conta criada e uma mensagem de sucesso com status 201
            return response()->json([
                'status' => true,
                'bill' => $bill,
                'message' => 'Conta cadastrada com sucesso!'
            ], 201);
        } catch (Exception $e) {

            // Operação não é concluída com êxito
            DB::rollBack();

            // Salvar log
            Log::notice('Conta não cadastrada.', ['action_user_id' => Auth::id(), 'error' => $e->getMessage()]);

            // Retorna uma mensagem de erro com status 400
            return response()->json([
                'status' => true,
                'message' => 'Conta não cadastrada!'
            ], 400);
        }
    }

    /**
     * Atualiza uma conta existente.
     *
     * Este método atualiza uma conta existente com os dados fornecidos na solicitação
     * e retorna a conta atualizada em formato JSON.
     *
     * @param  \App\Http\Requests\BillRequest  $request Os dados da conta a serem atualizados
     * @param  \App\Models\Bill  $bill O objeto da conta a ser atualizada
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(BillRequest $request, Bill $bill): JsonResponse
    {
        // Iniciar a transação
        DB::beginTransaction();

        try {

            // Editar o registro no banco de dados
            $bill->update([
                'name' => $request->name,
                'bill_value' => $request->bill_value,
                'due_date' => $request->due_date
            ]);

            // Operação é concluída com êxito
            DB::commit();

            // Salvar log
            Log::info('Conta editada.', ['bill_id' => $bill->id, 'action_user_id' => Auth::id()]);

            // Retorna os dados da conta editada e uma mensagem de sucesso com status 200
            return response()->json([
                'status' => true,
                'bill' => $bill,
                'message' => 'Conta editada com sucesso!'
            ], 200);
        } catch (Exception $e) {
            // Operação não é concluída com êxito
            DB::rollBack();

            // Salvar log
            Log::notice('Conta não editada.', ['action_user_id' => Auth::id(), 'error' => $e->getMessage()]);

            // Retorna uma mensagem de erro com status 400
            return response()->json([
                'status' => true,
                'message' => 'Conta não editada!'
            ], 400);
        }
    }

    /**
     * Exclui uma conta existente.
     *
     * Este método exclui uma conta existente do banco de dados e retorna a conta excluída em formato JSON.
     *
     * @param  \App\Models\Bill  $bill O objeto da conta a ser excluída
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Bill $bill): JsonResponse
    {
        try {

            // Excluir o registro do banco de dados
            $bill->delete();

            // Salvar log
            Log::info('Conta apagada.', ['bill_id' => $bill->id, 'action_user_id' => Auth::id()]);

            // Retorna os dados da conta apagada e uma mensagem de sucesso com status 200
            return response()->json([
                'status' => true,
                'bill' => $bill,
                'message' => 'Conta apagada com sucesso!'
            ], 200);
        } catch (Exception $e) {
            // Operação não é concluída com êxito
            DB::rollBack();

            // Salvar log
            Log::notice('Conta não apagada.', ['action_user_id' => Auth::id(), 'error' => $e->getMessage()]);

            // Retorna uma mensagem de erro com status 400
            return response()->json([
                'status' => true,
                'message' => 'Conta não apagada!'
            ], 400);
        }
    }
}
