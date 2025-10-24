<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$notification_count = 0;
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'ArtSync'); ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/dashboard.css">
    <?php if (isset($currentPage) && ($currentPage === 'login' || $currentPage === 'register')): ?>
        <link rel="stylesheet" href="/css/login_register.css">
    <?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="<?php echo ($currentPage === 'login' || $currentPage === 'register') ? 'centered-body' : ''; ?>">

    <?php if (isset($_SESSION['user_id'])): // Layout do Dashboard (Logado) ?>
        <div class="dashboard-container">
            <aside class="sidebar">
                <a href="/dashboard" class="logo">
                    <img src="/images/artsync.png" alt="Art Sync Logo" style="height: 50px; margin-bottom: 30px;">
                </a>
                <nav>
                    <ul>
                        <li class="<?php echo ($currentPage === 'dashboard') ? 'active' : ''; ?>"><a href="/dashboard"><i
                                    class="fas fa-home fa-fw"></i> Dashboard</a></li>
                        <li class="<?php echo ($currentPage === 'portfolio') ? 'active' : ''; ?>"><a href="/portfolio"><i
                                    class="fas fa-user-circle fa-fw"></i> Portfólio</a></li>
                        <li class="<?php echo ($currentPage === 'schedule') ? 'active' : ''; ?>"><a href="/schedule"><i
                                    class="fas fa-calendar-alt fa-fw"></i> Agenda</a></li>
                        <li class="<?php echo ($currentPage === 'ia') ? 'active' : ''; ?>"><a href="/ai"><i
                                    class="fas fa-robot fa-fw"></i> IA de Carreira</a></li>
                        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                            <li class="<?php echo ($currentPage === 'admin') ? 'active' : ''; ?>"><a href="/admin"><i
                                        class="fas fa-user-shield fa-fw"></i> Admin</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <div class="sidebar-footer">
                    <div class="notifications">
                        <a href="#" id="notification-bell">🔔
                            <?php if ($notification_count > 0): ?>
                                <span class="notification-count"><?php echo $notification_count; ?></span>
                            <?php endif; ?>
                        </a>
                        <div class="notification-dropdown" id="notification-dropdown">
                            <p>Nenhuma notificação nova.</p>
                        </div>
                    </div>
                    <a href="/logout" class="btn-logout"><i class="fas fa-sign-out-alt fa-fw"></i> Sair</a>
                </div>
            </aside>
            <main class="main-content">
            <?php else: ?>
            <?php endif; ?>