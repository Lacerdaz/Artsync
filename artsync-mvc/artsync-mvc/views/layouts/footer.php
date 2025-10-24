<?php
// SÓ FECHA AS TAGS DO LAYOUT DO DASHBOARD SE O USUÁRIO ESTAVA LOGADO
// (Ou seja, se o IF lá no header.php foi verdadeiro)
if (isset($_SESSION['user_id'])):
    ?>
    </main>
    </div>
    <script>
        // Lógica para o sino de notificação
        const notificationBell = document.getElementById('notification-bell');
        const notificationDropdown = document.getElementById('notification-dropdown');

        if (notificationBell && notificationDropdown) {
            // Abre/Fecha o dropdown ao clicar no sino
            notificationBell.addEventListener('click', function (e) {
                e.preventDefault(); // Impede o link de navegar
                e.stopPropagation(); // Impede que o clique feche imediatamente
                notificationDropdown.classList.toggle('show');
            });

            // Fecha o dropdown se clicar em qualquer outro lugar fora dele
            window.addEventListener('click', function (event) {
                // Verifica se o clique NÃO foi no sino E NÃO foi dentro do dropdown
                if (!notificationBell.contains(event.target) && !notificationDropdown.contains(event.target)) {
                    // Se o dropdown estiver visível, esconde
                    if (notificationDropdown.classList.contains('show')) {
                        notificationDropdown.classList.remove('show');
                    }
                }
            });
        }

        // Lógica para o spinner da IA (só funciona se a página atual tiver esses IDs)
        const aiForm = document.getElementById('ai-form');
        const aiSpinner = document.getElementById('ai-spinner');
        if (aiForm && aiSpinner) { // Garante que ambos existam
            aiForm.addEventListener('submit', function () {
                // Mostra o spinner quando o formulário é enviado
                aiSpinner.style.display = 'flex';
            });
        }

        // Dentro do <script> em views/layouts/footer.php

        // Lógica para o spinner da IA (mantém)
        const aiForm = document.getElementById('ai-form');
        const aiSpinner = document.getElementById('ai-spinner');
        // ... (código do spinner) ...

        // --- NOVO CÓDIGO PARA UPLOAD ---
        const uploadBtn = document.getElementById('upload-btn');
        const fileInput = document.getElementById('ai-file-input');
        const questionTextarea = document.getElementById('user_question'); // Pegamos a textarea

        if (uploadBtn && fileInput) {
            // Ao clicar no clipe, clica no input escondido
            uploadBtn.addEventListener('click', () => {
                fileInput.click();
            });

            // Opcional: Mostra o nome do arquivo selecionado na textarea (ou em outro lugar)
            fileInput.addEventListener('change', () => {
                if (fileInput.files.length > 0) {
                    const fileName = fileInput.files[0].name;
                    // Adiciona o nome do arquivo à pergunta (ajuste conforme preferir)
                    if (questionTextarea) {
                        questionTextarea.value += ` (Arquivo anexado: ${fileName})`;
                        // Ajusta a altura da textarea se necessário
                        questionTextarea.style.height = 'auto';
                        questionTextarea.style.height = `${questionTextarea.scrollHeight}px`;
                    }
                    console.log('Arquivo selecionado:', fileName);
                }
            });
        }
        // --- FIM DO CÓDIGO PARA UPLOAD ---

    </script>
<?php
    // FIM DO IF 'isset($_SESSION['user_id'])'
endif;
?>

</body>

</html>