<?php
// dashboard.php - Dashboard principal en raíz
require_once __DIR__ . '/includes/init.php';
require_login();

$user = current_user($pdo);
$tenant = null;

if (!empty($user['tenant_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM tenants WHERE id = :id AND deleted_at IS NULL LIMIT 1");
    $stmt->execute([':id' => $user['tenant_id']]);
    $tenant = $stmt->fetch();
}

$pageTitle = 'Dashboard - MassolaCommerce';

include_once __DIR__ . '/header.php';
?>

<div class="container" style="max-width: 1200px; margin: 40px auto; padding: 20px;">
    
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        <h1 style="margin: 0 0 10px 0; font-size: 32px;">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </h1>
        <p style="margin: 0; opacity: 0.9; font-size: 18px;">
            Bienvenido, <strong><?= sanitize($user['username'] ?? $user['email']) ?></strong>
        </p>
        <?php if ($tenant): ?>
            <p style="margin: 10px 0 0 0; opacity: 0.8;">
                <i class="fas fa-store"></i> Administrando: <?= sanitize($tenant['name']) ?>
            </p>
        <?php endif; ?>
    </div>
    
    <?php if ($tenant): ?>
        
        <!-- Menu de navegación del dashboard -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px;">
            
            <a href="/products.php" style="background: white; padding: 30px; border-radius: 12px; text-decoration: none; color: #333; box-shadow: 0 5px 15px rgba(0,0,0,0.08); transition: transform 0.2s, box-shadow 0.2s; border-left: 4px solid #667eea;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                        <i class="fas fa-box" style="font-size: 24px; color: white;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 20px;">Productos</h3>
                </div>
                <p style="margin: 0; color: #666; font-size: 14px;">Gestiona tu inventario y catálogo de productos</p>
            </a>
            
            <a href="/orders.php" style="background: white; padding: 30px; border-radius: 12px; text-decoration: none; color: #333; box-shadow: 0 5px 15px rgba(0,0,0,0.08); transition: transform 0.2s, box-shadow 0.2s; border-left: 4px solid #28a745;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                        <i class="fas fa-shopping-cart" style="font-size: 24px; color: white;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 20px;">Pedidos</h3>
                </div>
                <p style="margin: 0; color: #666; font-size: 14px;">Administra y procesa tus pedidos</p>
            </a>
            
            <a href="/settings.php" style="background: white; padding: 30px; border-radius: 12px; text-decoration: none; color: #333; box-shadow: 0 5px 15px rgba(0,0,0,0.08); transition: transform 0.2s, box-shadow 0.2s; border-left: 4px solid #ffc107;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                        <i class="fas fa-cog" style="font-size: 24px; color: white;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 20px;">Configuración</h3>
                </div>
                <p style="margin: 0; color: #666; font-size: 14px;">Personaliza tu tienda y preferencias</p>
            </a>
            
            <a href="/tickets.php" style="background: white; padding: 30px; border-radius: 12px; text-decoration: none; color: #333; box-shadow: 0 5px 15px rgba(0,0,0,0.08); transition: transform 0.2s, box-shadow 0.2s; border-left: 4px solid #17a2b8;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                        <i class="fas fa-ticket-alt" style="font-size: 24px; color: white;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 20px;">Soporte</h3>
                </div>
                <p style="margin: 0; color: #666; font-size: 14px;">Contacta con el equipo de soporte</p>
            </a>
            
        </div>
        
        <!-- Estadísticas rápidas -->
        <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.08);">
            <h2 style="margin: 0 0 20px 0; color: #333;"><i class="fas fa-chart-line"></i> Resumen de Actividad</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                
                <?php
                // Obtener estadísticas
                $stats_products = $pdo->prepare("SELECT COUNT(*) as total FROM products WHERE tenant_id = :tid AND deleted_at IS NULL");
                $stats_products->execute([':tid' => $tenant['id']]);
                $total_products = $stats_products->fetch()['total'] ?? 0;
                
                $stats_orders = $pdo->prepare("SELECT COUNT(*) as total FROM orders WHERE tenant_id = :tid");
                $stats_orders->execute([':tid' => $tenant['id']]);
                $total_orders = $stats_orders->fetch()['total'] ?? 0;
                
                $stats_revenue = $pdo->prepare("SELECT SUM(total) as revenue FROM orders WHERE tenant_id = :tid AND status = 'paid'");
                $stats_revenue->execute([':tid' => $tenant['id']]);
                $total_revenue = $stats_revenue->fetch()['revenue'] ?? 0;
                ?>
                
                <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; text-align: center;">
                    <div style="font-size: 36px; font-weight: bold; color: #667eea; margin-bottom: 5px;"><?= $total_products ?></div>
                    <div style="color: #666; font-size: 14px;">Productos Activos</div>
                </div>
                
                <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; text-align: center;">
                    <div style="font-size: 36px; font-weight: bold; color: #28a745; margin-bottom: 5px;"><?= $total_orders ?></div>
                    <div style="color: #666; font-size: 14px;">Pedidos Totales</div>
                </div>
                
                <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; text-align: center;">
                    <div style="font-size: 36px; font-weight: bold; color: #ffc107; margin-bottom: 5px;">$<?= number_format($total_revenue, 2) ?></div>
                    <div style="color: #666; font-size: 14px;">Ingresos Totales</div>
                </div>
                
            </div>
        </div>
        
    <?php else: ?>
        
        <!-- Usuario sin tienda -->
        <div style="background: white; padding: 50px; border-radius: 15px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.08);">
            <div style="font-size: 64px; color: #e0e0e0; margin-bottom: 20px;">
                <i class="fas fa-store-slash"></i>
            </div>
            <h2 style="color: #333; margin-bottom: 15px;">No administras ninguna tienda</h2>
            <p style="color: #666; margin-bottom: 30px; font-size: 16px;">
                Para comenzar a vender, necesitas crear tu tienda primero.
            </p>
            <a href="/create_store.php" class="btn btn-primary" style="display: inline-block; padding: 15px 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 8px; font-size: 18px; font-weight: 600;">
                <i class="fas fa-plus-circle"></i> Crear Mi Tienda
            </a>
        </div>
        
    <?php endif; ?>
    
</div>

<style>
    a[href*="/products.php"]:hover,
    a[href*="/orders.php"]:hover,
    a[href*="/settings.php"]:hover,
    a[href*="/tickets.php"]:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
</style>

<?php include_once __DIR__ . '/footer.php'; ?>
