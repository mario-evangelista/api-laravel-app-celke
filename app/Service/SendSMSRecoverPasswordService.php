<?php

namespace App\Service;

use Illuminate\Support\Facades\Log;

class SendSMSRecoverPasswordService
{

    /**
     * Envia SMS com o código para recuperação de senha.
     *
     * Este método utiliza a API da iAgente para enviar um SMS contendo um código de recuperação de senha
     * para o número de celular fornecido. A mensagem inclui o código e a validade do código formatada
     * como data e hora.
     *
     * @param string $cellphone_number O número de celular para o qual o SMS será enviado.
     * @param string $code O código de recuperação de senha.
     * @param string $formattedDate A data formatada até a qual o código é válido.
     * @param string $formattedTime A hora formatada até a qual o código é válido.
     * @return void
     */
    public function sendSMSRecoverPassword($cellphone_number, $code, $formattedDate, $formattedTime): void
    {

        // Credenciais do servidor de SMS
        $iagenteUser = env('IAGENTE_USER');
        $iagentePassword = urlencode(env('IAGENTE_PASSWORD'));

        // Codificar os dados no formato de um formulário www
        $message = urlencode("Celke: Não compartilhe este dado com ninguem. Seu código e " . $code . " Valido ate " . $formattedDate . " as " . $formattedTime);

        // Concatenar a url da api com a variável carregando o conteúdo da mensagem
        $url_api = "https://api.iagentesms.com.br/webservices/http.php?metodo=envio&usuario=$iagenteUser&senha=$iagentePassword&celular=$cellphone_number&mensagem=$message";

        // Realizar a requisição http passando os parâmetros informados
        $api_http = file_get_contents($url_api);

        // Salvar log com o resultado da requisição
        Log::info('SMS enviado com o código recuperar senha.', ['message' => $api_http]);
    }
}