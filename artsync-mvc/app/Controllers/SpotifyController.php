<?php

namespace App\Controllers;

class SpotifyController extends AuthController
{
    private string $clientId = 'SEU_CLIENT_ID_AQUI';
    private string $clientSecret = 'SEU_CLIENT_SECRET_AQUI';
    private string $redirectUri = 'http://artsync.local/spotify_callback';

    public function __construct()
    {
        parent::__construct();
        $this->checkAuth();
    }

    // Etapa 1: Redireciona o usuário para login no Spotify
    public function connect(): void
    {
        $scopes = implode(' ', [
            'user-read-email',
            'user-top-read',
            'playlist-read-private',
        ]);

        $authUrl = "https://accounts.spotify.com/authorize?" . http_build_query([
            'response_type' => 'code',
            'client_id' => $this->clientId,
            'scope' => $scopes,
            'redirect_uri' => $this->redirectUri,
        ]);

        header('Location: ' . $authUrl);
        exit;
    }

    // Etapa 2: Callback - recebe o code e obtém o token de acesso
    public function callback(): void
    {
        if (!isset($_GET['code'])) {
            echo "<h3>Erro: Nenhum código de autorização recebido do Spotify.</h3>";
            exit;
        }

        $code = $_GET['code'];

        $ch = curl_init('https://accounts.spotify.com/api/token');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $this->redirectUri,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]),
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($error || $status !== 200) {
            echo "<pre>Erro ao conectar ao Spotify: " . htmlspecialchars($error ?: $response) . "</pre>";
            exit;
        }

        $data = json_decode($response, true);
        $_SESSION['spotify_access_token'] = $data['access_token'] ?? null;

        header('Location: /dashboard');
        exit;
    }
}
