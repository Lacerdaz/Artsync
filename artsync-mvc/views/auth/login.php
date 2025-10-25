<?php
// Define variables specific to this page BEFORE including the header
$pageTitle = 'Login';
$currentPage = 'login'; // Used by header.php to know which CSS to load and which body class to use

// Includes <!DOCTYPE>, <html>, <head> (with CSS links), and opening <body> tag
require __DIR__ . '/../layouts/header.php'; 
?>

<div class="form-container">
    
    <a href="/" class="logo" style="display: block; text-align: center; margin-bottom: 25px;"> 
        <img src="/images/artsync.png" alt="Art Sync Logo" style="height: 55px; width: auto;">
    </a>
    
    <h2>Acesse sua conta</h2>

    <?php if (isset($error)): // Display login errors passed from AuthController ?>
        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="/login" method="POST">
        <div class="input-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="input-group">
            <label for="password">Senha</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn">Entrar</button>
    </form>

    <div class="switch-form" style="margin-top: 15px;">
        <p>Não tem uma conta? <a href="/register">Cadastre-se</a></p>
    </div>

</div> 
<?php 
// Includes closing </body> and </html> tags
require __DIR__ . '/../layouts/footer.php'; 
?>