<?php

namespace App\Controllers;

use PDO;
use PDOException;

class ProfileController extends AuthController
{
    private PDO $pdo;

    public function __construct()
    {
        parent::__construct();
        $this->checkAuth();

        // Ajuste aqui se sua conexão for diferente
        $host = 'localhost';
        $dbname = 'artsync_db';
        $username = 'root';
        $password = '';

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Erro de conexão ao banco: ' . $e->getMessage());
        }
    }

    /**
     * GET /profile/edit
     */
    public function edit(): void
    {
        // carrega dados atuais do usuário
        $stmt = $this->pdo->prepare("SELECT id, artist_name, email FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->view('profile/edit', [
            'pageTitle'   => 'Editar Perfil',
            'currentPage' => 'dashboard',
            'user'        => $user,
            'feedback'    => $_SESSION['feedback'] ?? null
        ]);
        unset($_SESSION['feedback']);
    }

    /**
     * POST /profile/update
     */
    public function update(): void
    {
        $userId     = (int) $_SESSION['user_id'];
        $artistName = trim($_POST['artist_name'] ?? '');
        $email      = trim($_POST['email'] ?? '');
        $password   = $_POST['password'] ?? '';
        $confirm    = $_POST['password_confirm'] ?? '';

        // validações simples
        if ($artistName === '' || $email === '') {
            $_SESSION['feedback'] = ['type' => 'error', 'message' => 'Nome e e-mail são obrigatórios.'];
            header('Location: /profile/edit');
            exit;
        }

        if ($password !== '' && $password !== $confirm) {
            $_SESSION['feedback'] = ['type' => 'error', 'message' => 'As senhas não conferem.'];
            header('Location: /profile/edit');
            exit;
        }

        // verifica se email já está em uso por outro usuário
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = :email AND id != :id LIMIT 1");
        $stmt->execute([':email' => $email, ':id' => $userId]);
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            $_SESSION['feedback'] = ['type' => 'error', 'message' => 'Este e-mail já está em uso por outro usuário.'];
            header('Location: /profile/edit');
            exit;
        }

        // prepara UPDATE
        if ($password !== '') {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $sql = "UPDATE users SET artist_name = :name, email = :email, password = :pwd WHERE id = :id";
            $params = [':name' => $artistName, ':email' => $email, ':pwd' => $hash, ':id' => $userId];
        } else {
            $sql = "UPDATE users SET artist_name = :name, email = :email WHERE id = :id";
            $params = [':name' => $artistName, ':email' => $email, ':id' => $userId];
        }

        try {
            $this->pdo->prepare($sql)->execute($params);
        } catch (PDOException $e) {
            $_SESSION['feedback'] = ['type' => 'error', 'message' => 'Erro ao atualizar dados: ' . $e->getMessage()];
            header('Location: /profile/edit');
            exit;
        }

        // upload de avatar (opcional)
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $tmp = $_FILES['avatar']['tmp_name'];
            $mime = mime_content_type($tmp);

            $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
            if (!isset($allowed[$mime])) {
                $_SESSION['feedback'] = ['type' => 'error', 'message' => 'Tipo de imagem inválido. Envie JPG, PNG ou WEBP.'];
                header('Location: /profile/edit');
                exit;
            }

            $ext = $allowed[$mime];
            $dir = __DIR__ . '/../../public/uploads/profile';
            if (!is_dir($dir)) {
                @mkdir($dir, 0777, true);
            }

            $destRelative = "/uploads/profile/user_{$userId}.{$ext}";
            $destAbs      = __DIR__ . "/../../public" . $destRelative;

            // remove outros possíveis formatos anteriores
            foreach (['jpg','jpeg','png','webp'] as $old) {
                $oldFile = __DIR__ . "/../../public/uploads/profile/user_{$userId}.{$old}";
                if (file_exists($oldFile)) @unlink($oldFile);
            }

            if (!move_uploaded_file($tmp, $destAbs)) {
                $_SESSION['feedback'] = ['type' => 'error', 'message' => 'Falha ao salvar a foto de perfil.'];
                header('Location: /profile/edit');
                exit;
            }

            // atualiza sessão pra refletir de imediato
            $_SESSION['user_profile'] = $destRelative . '?t=' . time();
        }

        // atualiza sessão do nome/email
        $_SESSION['artist_name'] = $artistName;
        $_SESSION['email'] = $email;

        $_SESSION['feedback'] = ['type' => 'success', 'message' => 'Perfil atualizado com sucesso!'];
        header('Location: /profile/edit');
        exit;
    }
}
