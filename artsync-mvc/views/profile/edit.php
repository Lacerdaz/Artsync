<?php
/** @var array $user */
/** @var array|null $feedback */
$avatarPath = '/uploads/profile/default.png';
$userId = (int) ($_SESSION['user_id'] ?? 0);

// tenta encontrar avatar existente (qualquer extensão)
$base = __DIR__ . '/../../public/uploads/profile';
$web  = '/uploads/profile';
$found = null;
foreach (['jpg','jpeg','png','webp'] as $ext) {
    $p = "{$base}/user_{$userId}.{$ext}";
    if (file_exists($p)) { $found = "{$web}/user_{$userId}.{$ext}"; break; }
}
if ($found) {
    $avatarPath = $found . '?t=' . time();
} elseif (!empty($_SESSION['user_profile'])) {
    $avatarPath = $_SESSION['user_profile'];
}
?>
<section class="card" style="max-width:860px; margin:0 auto;">
    <h3 style="margin-top:0;">Editar Perfil</h3>
    <?php if (!empty($feedback)): ?>
        <div class="<?= $feedback['type'] === 'success' ? 'success-message' : 'error-message' ?>" style="margin:15px 0;">
            <?= htmlspecialchars($feedback['message']); ?>
        </div>
    <?php endif; ?>

    <form action="/profile/update" method="POST" enctype="multipart/form-data">
        <div class="profile-edit-grid">
            <div class="profile-photo">
                <div class="avatar-preview">
                    <img id="avatarPreview" src="<?= htmlspecialchars($avatarPath); ?>" alt="Avatar">
                </div>
                <label class="btn" for="avatarInput" style="cursor:pointer; display:inline-block; margin-top:12px;">Escolher nova foto</label>
                <input id="avatarInput" type="file" name="avatar" accept="image/*" style="display:none;">
                <p class="hint">Formatos aceitos: JPG, PNG, WEBP. Tamanho recomendado: 512x512.</p>
            </div>

            <div class="profile-fields">
                <div class="input-group">
                    <label>Nome de exibição</label>
                    <input type="text" name="artist_name" value="<?= htmlspecialchars($user['artist_name'] ?? ''); ?>" required>
                </div>

                <div class="input-group">
                    <label>E-mail</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? ''); ?>" required>
                </div>

                <div class="input-group">
                    <label>Nova senha (opcional)</label>
                    <input type="password" name="password" placeholder="Deixe em branco para manter">
                </div>

                <div class="input-group">
                    <label>Confirmar nova senha</label>
                    <input type="password" name="password_confirm" placeholder="Repita a nova senha">
                </div>

                <button class="btn" type="submit">Salvar alterações</button>
            </div>
        </div>
    </form>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('avatarInput');
    const img   = document.getElementById('avatarPreview');
    if (input && img) {
        input.addEventListener('change', () => {
            const file = input.files?.[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => img.src = e.target.result;
            reader.readAsDataURL(file);
        });
    }
});
</script>

<style>
.profile-edit-grid{
    display:grid;
    grid-template-columns: 280px 1fr;
    gap:24px;
}
.profile-photo{
    display:flex; flex-direction:column; align-items:center;
}
.avatar-preview{
    width:180px; height:180px; border-radius:50%;
    border:2px solid var(--border-color);
    overflow:hidden; background:rgba(255,255,255,.04);
}
.avatar-preview img{ width:100%; height:100%; object-fit:cover; }
.profile-fields .input-group{ margin-bottom:16px; }
.hint{ font-size:.85em; color:var(--secondary-text-color); margin-top:8px; text-align:center; }
@media (max-width: 840px){
    .profile-edit-grid{ grid-template-columns:1fr; }
}
</style>
