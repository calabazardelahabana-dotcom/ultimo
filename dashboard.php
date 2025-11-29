<?php
// dashboard.php - Panel principal
require_once __DIR__ . '/includes/init.php';
require_login();

$user = current_user($pdo);
$pageTitle = 'Dashboard - MassolaCommerce';

// Obtener estadísticas básicas
$stats = [
    'products' => 0,
    'orders' => 0,
    'revenue' => 0,
    'tenants' => 0
];

if (is_superadmin()) {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM tenants WHERE deleted_at IS NULL");
    $stats['tenants'] = $stmt->fetch()['total'];
}

// Obtener tenant del usuario
$tenantStmt = $pdo->prepare("SELECT t.* FROM tenants t JOIN users u ON u.tenant_id = t.id WHERE u.id = :uid LIMIT 1");
$tenantStmt->execute([':uid' => $_SESSION['user_id']]);
$tenant = $tenantStmt->fetch();

if ($tenant) {
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM products WHERE tenant_id = :tid AND deleted_at IS NULL");
    $stmt->execute([':tid' => $tenant['id']]);
    $stats['products'] = $stmt->fetch()['total'];
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM orders WHERE tenant_id = :tid");
    $stmt->execute([':tid' => $tenant['id']]);
    $stats['orders'] = $stmt->fetch()['total'];
    
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(total_amount), 0) as total FROM orders WHERE tenant_id = :tid AND status = 'completed'");
    $stmt->execute([':tid' => $tenant['id']]);
    $stats['revenue'] = $stmt->fetch()['total'];
}

include_once __DIR__ . '/header.php';
?>

<style>
    .dashboard-container {
        padding: 40px 20px;
    }
    
    .welcome-banner {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px;
        border-radius: 15px;
        margin-bottom: 40px;
        text-align: center;
    }
    
    .welcome-banner h1 {
        font-size: 2.5em;
        margin-bottom: 10px;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }
    
    .stat-card {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        transition: transform 0.3s;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.12);
    }
    
    .stat-icon {
        font-size: 2.5em;
        margin-bottom: 15px;
        opacity: 0.8;
    }
    
    .stat-value {
        font-size: 2.5em;
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .stat-label {
        color: #718096;
        font-size: 1.1em;
    }
    
    .quick-actions {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    
    .quick-actions h2 {
        margin-bottom: 20px;
        color: #2d3748;
    }
    
    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }
    
    .action-btn {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 20px;
        background: #f7fafc;
        border-radius: 8px;
        text-decoration: none;
        color: #2d3748;
        transition: all 0.3s;
        border: 2px solid transparent;
    }
    
    .action-btn:hover {
        background: #667eea;
        color: white;
        border-color: #667eea;
        transform: translateX(5px);
    }
    
    .action-btn i {
        font-size: 1.8em;
    }
</style>

<div class="dashboard-container">
    <div class="container">
        <?php if (isset($_GET['welcome'])): ?>
            <div class="welcome-banner">
                <h1>🎉 ¡Bienvenido a MassolaCommerce!</h1>
                <p style="font-size: 1.2em;">Tu cuenta ha sido creada exitosamente</p>
            </div>
        <?php else: ?>
            <div class="welcome-banner">
                <h1>Hola, <?= sanitize($user['username']) ?>!</h1>
                <p style="font-size: 1.2em;">Bienvenido de vuelta a tu panel de control</p>
            </div>
        <?php endif; ?>
        
        <div class="stats-grid">
            <?php if (is_superadmin()): ?>
                <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <div class="stat-icon"><i class="fas fa-store"></i></div>
                    <div class="stat-value"><?= $stats['tenants'] ?></div>
                    <div class="stat-label" style="color: rgba(255,255,255,0.9);">Tiendas Activas</div>
                </div>
            <?php endif; ?>
            
            <div class="stat-card" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white;">
                <div class="stat-icon"><i class="fas fa-box"></i></div>
                <div class="stat-value"><?= $stats['products'] ?></div>
                <div class="stat-label" style="color: rgba(255,255,255,0.9);">Productos</div>
            </div>
            
            <div class="stat-card" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white;">
                <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
                <div class="stat-value"><?= $stats['orders'] ?></div>
                <div class="stat-label" style="color: rgba(255,255,255,0.9);">Pedidos</div>
            </div>
            
            <div class="stat-card" style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white;">
                <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                <div class="stat-value">$<?= number_format($stats['revenue'], 2) ?></div>
                <div class="stat-label" style="color: rgba(255,255,255,0.9);">Ingresos</div>
            </div>
        </div>
        
        <div class="quick-actions">
            <h2><i class="fas fa-bolt"></i> Acciones Rápidas</h2>
            <div class="actions-grid">
                <?php if (is_superadmin()): ?>
                    <a href="/admin/tenants.php" class="action-btn">
                        <i class="fas fa-store"></i>
                        <span>Gestionar Tiendas</span>
                    </a>
                    <a href="/admin/users.php" class="action-btn">
                        <i class="fas fa-users"></i>
                        <span>Usuarios</span>
                    </a>
                    <a href="/admin/plans.php" class="action-btn">
                        <i class="fas fa-tags"></i>
                        <span>Planes</span>
                    </a>
                    <a href="/admin/settings.php" class="action-btn">
                        <i class="fas fa-cog"></i>
                        <span>Configuración</span>
                    </a>
                <?php else: ?>
                    <a href="/products.php" class="action-btn">
                        <i class="fas fa-box"></i>
                        <span>Mis Productos</span>
                    </a>
                    <a href="/orders.php" class="action-btn">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Pedidos</span>
                    </a>
                    <a href="/settings.php" class="action-btn">
                        <i class="fas fa-cog"></i>
                        <span>Configuración</span>
                    </a>
                    <a href="/reports.php" class="action-btn">
                        <i class="fas fa-chart-line"></i>
                        <span>Reportes</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/footer.php'; ?>
