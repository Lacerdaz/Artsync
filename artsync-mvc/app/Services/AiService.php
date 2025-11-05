<?php

namespace App\Services;

/**
 * Classe de serviço para lidar com a lógica de negócio da IA de Carreira.
 * Ela isola a lógica de chamada de API do Controller.
 */
class AiService {
    
    /**
     * Pega um conselho de carreira da API do Google Gemini.
     * @param string $user_question A pergunta do usuário.
     * @return string A resposta formatada da IA ou uma mensagem de erro.
     */
    public function getCareerAdvice(string $user_question): string {
        // Retorna cedo se a pergunta estiver vazia
        if (empty($user_question)) {
            return "Por favor, faça uma pergunta.";
        }
        
        // ** SUA CHAVE DE API FOI INSERIDA AQUI **
        $api_key = 'SUA_CHAVE_DE_API_AQUI'; 
        // URL da API do Gemini (usando o modelo mais recente)
        $api_url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=' . $api_key;
        
        // Monta o prompt (instrução) para a IA
        $prompt = "Você é um mentor de carreiras para artistas musicais independentes. Dê conselhos práticos, curtos e inspiradores em português do Brasil. Não use markdown ou formatação especial (como negrito ou listas). A pergunta do artista é: " . $user_question;
        
        // Prepara os dados para enviar à API no formato JSON esperado
        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ];
        
        // --- INÍCIO DA CHAMADA DA API USANDO cURL ---
        $ch = curl_init($api_url); // Inicializa o cURL
        
        // Configura as opções do cURL:
        // Define o cabeçalho como JSON
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        // Define o método como POST
        curl_setopt($ch, CURLOPT_POST, true); 
        // Envia os dados ($data) convertidos para JSON
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        // Diz ao cURL para retornar a resposta como uma string em vez de imprimir na tela
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // **IMPORTANTE PARA WAMP/XAMPP:** Desabilita a verificação do certificado SSL.
        // NÃO FAÇA ISSO EM UM SITE REAL (PRODUÇÃO), mas é necessário para testes locais.
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Também desabilita verificação do host
        
        // Executa a requisição e pega a resposta
        $response = curl_exec($ch);
        // Pega o código de status HTTP (200 = OK, 4xx/5xx = Erro)
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // Pega informações de erro do cURL, se houver
        $curl_error = curl_error($ch); 
        
        // Fecha a conexão cURL
        curl_close($ch);
        // --- FIM DA CHAMADA DA API ---

        // Verifica se a chamada foi bem-sucedida (código 200)
        if ($http_code == 200 && $response) {
            // Decodifica a resposta JSON em um array PHP
            $result = json_decode($response, true);
            
            // Tenta extrair o texto da resposta da IA da estrutura JSON do Gemini
            // Usa o operador '??' para fornecer um valor padrão se a estrutura não for encontrada
            $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if ($text !== null) {
                 // Se encontrou o texto, formata para exibição no HTML
                 // htmlspecialchars protege contra XSS, nl2br converte quebras de linha em <br>
                return nl2br(htmlspecialchars($text));
            } else {
                // Se a estrutura da resposta JSON foi inesperada
                return "Desculpe, a IA respondeu, mas não consegui entender a resposta. Resposta recebida: " . htmlspecialchars($response);
            }
        } else {
            // Se houve erro na chamada da API (HTTP não foi 200 ou cURL falhou)
            $error_message = "Ocorreu um erro ao contatar a IA (HTTP Status: {$http_code}). ";
            if ($curl_error) {
                $error_message .= "Erro cURL: " . htmlspecialchars($curl_error);
            } else {
                 $error_message .= "Resposta do servidor: " . htmlspecialchars($response);
            }
            // Verifica se o erro pode ser a chave de API (erros comuns 400 ou 403)
            if ($http_code == 400 || $http_code == 403) {
                 $error_message .= " Por favor, verifique sua chave de API.";
            }
            return $error_message;
        }
    }
}