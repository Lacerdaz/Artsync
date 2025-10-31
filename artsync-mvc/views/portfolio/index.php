<?php require __DIR__ . '/../layouts/header.php'; // Carrega header e menu ?>

<header class="main-header">
    <h2>Meu Portfólio</h2>
    <p>Adicione e gerencie suas melhores fotos e vídeos.</p>
</header>

<?php if (isset($feedback)): ?>
    <div class="<?php echo $feedback['type'] === 'error' ? 'error-message' : 'success-message'; ?>">
        <?php echo htmlspecialchars($feedback['message']); ?>
    </div>
<?php endif; ?>

<div class="card">
    <h3>Adicionar Nova Mídia</h3>
    <form action="/portfolio/upload" method="post" enctype="multipart/form-data">
        <div class="input-group">
            <label for="title">Título da Mídia</label>
            <input type="text" id="title" name="title" required>
        </div>
        <div class="input-group">
            <label for="description">Descrição (opcional)</label>
            <textarea id="description" name="description" rows="3"></textarea>
        </div>
        <div class="input-group">
            <label for="media_file">Arquivo de Mídia (Imagem)</label>
            <input type="file" id="media_file" name="media_file" required accept="image/*"> 
        </div>
        <button type="submit" class="btn">Adicionar ao Portfólio</button>
    </form>
</div>

<div class="card">
    <h3>Minhas Mídias</h3>
    <div class="portfolio-grid">
        <?php if (empty($items)): // $items é passado pelo PortfolioController ?>
            <p>Você ainda não adicionou nenhuma mídia ao seu portfólio.</p>
        <?php else: ?>
            <?php foreach ($items as $item): // Loop pelos itens do portfólio ?>
                <div class="portfolio-item">
                    <img src="<?php echo htmlspecialchars($item->filePath); ?>" alt="<?php echo htmlspecialchars($item->title); ?>">
                    <div class="item-info">
                        <h4><?php echo htmlspecialchars($item->title); ?></h4>
                        <p><?php echo htmlspecialchars($item->description ?? ''); ?></p>
                        <div class="item-actions">
                            <a href="/portfolio/delete?id=<?php echo $item->id; ?>" 
                               class="action-btn delete" 
                               onclick="return confirm('Tem certeza que deseja excluir esta mídia?');">
                                Excluir
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<img src="<?= htmlspecialchars($item->filePath ?? '', ENT_QUOTES, 'UTF-8') ?>" 
     alt="<?= htmlspecialchars($item->title ?? '', ENT_QUOTES, 'UTF-8') ?>">

<h3><?= htmlspecialchars($item->title ?? '', ENT_QUOTES, 'UTF-8') ?></h3>
<p><?= htmlspecialchars($item->description ?? '', ENT_QUOTES, 'UTF-8') ?></p>

<a href="/portfolio/delete?id=<?= (int)($item->id ?? 0) ?>">Excluir</a>

<?php require __DIR__ . '/../layouts/footer.php'; // Carrega o final do HTML ?>