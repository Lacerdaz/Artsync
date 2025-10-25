<?php

namespace App\Controllers;

use App\Services\AiService; // Importa o Serviço de IA

class AiController extends AuthController
{

    private AiService $aiService;

    public function __construct()
    {
        parent::__construct();
        $this->checkAuth();
        $this->aiService = new AiService(); // Instancia o serviço
    }

    /**
     * Ação: Exibe a página da IA (sem resposta ainda).
     */
    public function index(): void
    {
        $this->view('ai/index', [
            'pageTitle' => 'IA de Carreira',
            'currentPage' => 'ia'
        ]);
    }

    /**
     * Ação: Processa a pergunta do formulário e exibe a resposta.
     */
    /**
     * Ação: Processa a pergunta do formulário (com possível anexo) e exibe a resposta.
     */
    public function ask(): void
    {
        $question = $_POST['user_question'] ?? '';
        $fileContent = null;
        $mimeType = null;
        $uploadedFileName = null; // Para mostrar na view que um arquivo foi enviado

        // Verifica se um arquivo foi enviado sem erros
        if (isset($_FILES['media_file']) && $_FILES['media_file']['error'] === UPLOAD_ERR_OK) {
            $tmpFilePath = $_FILES['media_file']['tmp_name'];
            // Tenta obter o tipo MIME real do arquivo (mais seguro que ['type'])
            $mimeType = mime_content_type($tmpFilePath);
            // Lê o conteúdo binário do arquivo
            $fileContent = file_get_contents($tmpFilePath);
            $uploadedFileName = $_FILES['media_file']['name']; // Guarda o nome original

            // Validação simples de tipo MIME (exemplo: permitir apenas imagens e áudio)
            if ($mimeType && !str_starts_with($mimeType, 'image/') && !str_starts_with($mimeType, 'audio/')) {
                $_SESSION['feedback'] = ['type' => 'error', 'message' => 'Tipo de arquivo não suportado. Anexe apenas imagens ou áudio.'];
                // Limpa os dados do arquivo inválido
                $fileContent = null;
                $mimeType = null;
                $uploadedFileName = null;
                // Continua para enviar apenas o texto à IA, mas exibe o erro
            }
        } elseif (isset($_FILES['media_file']) && $_FILES['media_file']['error'] !== UPLOAD_ERR_NO_FILE) {
            // Se houve um erro diferente de "nenhum arquivo enviado"
            $_SESSION['feedback'] = ['type' => 'error', 'message' => 'Erro no upload do arquivo: ' . $_FILES['media_file']['error']];
        }

        // Pede a resposta ao Serviço de IA, passando o conteúdo e tipo do arquivo (ou null)
        $response = $this->aiService->getCareerAdvice($question, $fileContent, $mimeType);

        // Chama a view, passando todos os dados necessários
        $this->view('ai/index', [
            'pageTitle' => 'IA de Carreira',
            'currentPage' => 'ia',
            'user_question' => $question, // A pergunta original
            'uploaded_file_name' => $uploadedFileName, // Nome do arquivo (ou null)
            'ai_response' => $response, // A resposta da IA
            'feedback' => $_SESSION['feedback'] ?? null // Mensagem de erro/sucesso
        ]);
        unset($_SESSION['feedback']); // Limpa feedback
    }
}