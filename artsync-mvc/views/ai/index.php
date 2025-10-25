<?php require __DIR__ . '/../layouts/header.php'; // Carrega header e menu ?>

<header class="main-header">
    <h2>IA de Carreira</h2>
    <p>Converse com um mentor de IA para impulsionar sua carreira musical.</p>
</header>

<section class="card gemini-style-card">

    <div class="user-bubble-form">
        <div class="avatar user-avatar">
            <?php
            // Pega as iniciais do nome do artista para o avatar
            $name_parts = explode(' ', $_SESSION['artist_name'] ?? 'Usuário');
            $initials = '';
            foreach ($name_parts as $part) {
                // Garante que pega apenas letras
                if (ctype_alpha(substr($part, 0, 1))) {
                    $initials .= strtoupper(substr($part, 0, 1));
                }
            }
            echo htmlspecialchars(substr($initials, 0, 2));
            ?>
        </div>
        <form action="/ai/ask" method="post" id="ai-form" enctype="multipart/form-data">
            <input type="file" id="ai-file-input" name="media_file" accept="image/*,audio/*" style="display: none;">

            <div class="user-bubble-form">
                <div class="avatar user-avatar">...</div>
                <textarea ...></textarea>
                <button type="button" id="upload-btn" class="upload-btn" title="Anexar arquivo"><i
                        class="fas fa-paperclip"></i></button>
                <button type="submit" name="ask_ai" title="Enviar Pergunta">...</button>
            </div>
        </form>
    </div>

    <?php if (isset($user_question) && !empty($user_question)): // Verifica se uma pergunta foi feita ?>
        <div class="conversation-history">
            <div class="chat-bubble user-bubble">
                <div class="avatar user-avatar"><?php echo htmlspecialchars(substr($initials, 0, 2)); ?></div>
                <p><?php echo htmlspecialchars($user_question); ?></p>
            </div>

            <div class="chat-bubble ai-bubble">
                <div class="avatar ai-avatar">✨</div>
                <?php if (empty($ai_response)): // Se a resposta ainda não chegou (ou deu erro) ?>
                    <div class="spinner-container" id="ai-spinner" style="display: flex;">
                        <div class="spinner"></div>
                    </div>
                <?php else: // Se a resposta chegou ?>
                    <p><?php echo $ai_response; // A resposta já vem tratada (nl2br, htmlspecialchars) do Controller/Service ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    <?php elseif (!isset($ai_response)): // Mensagem inicial se nenhuma pergunta foi feita ainda ?>
        <div class="conversation-history">
            <div class="chat-bubble ai-bubble">
                <div class="avatar ai-avatar"></div>
                <p>Olá! Sou seu mentor de carreira. Como posso te ajudar a brilhar hoje?</p>
            </div>
        </div>
    <?php endif; ?>

</section>

<?php require __DIR__ . '/../layouts/footer.php'; // Carrega o final do HTML ?>