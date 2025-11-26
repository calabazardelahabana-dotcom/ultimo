<?php
// index.php - Landing Page en raíz
require_once __DIR__ . '/includes/init.php';

$tenant = getTenantFromRequest($pdo); 
$pageTitle = 'MassolaCommerce | Plataforma de E-commerce y ERP para Emprendedores';

include_once __DIR__ . '/header.php';
?>

<section class="hero">
    <div class="container">
        <h1>Tu Plataforma ERP y E-commerce <br> Todo en Uno para el Éxito.</h1>
        <p>MassolaCommerce simplifica la gestión de tu negocio, permitiéndote controlar inventario, ventas y clientes de forma profesional, segura y con un diseño impecable.</p>
        
        <div class="hero-buttons">
            <a href="/register.php" class="btn btn-primary">Comenzar Prueba Gratuita</a>
            <a href="/login.php" class="btn btn-outline">Iniciar Sesión</a>
            <a href="#plans-section" class="btn btn-outline">Ver Planes</a>
        </div>
        
        <div class="hero-image-container" style="margin-top: 50px; max-width: 500px; margin-left: auto; margin-right: auto;">
            <img src="/assets/images/dashboard-mockup.png" alt="Panel de administración de MassolaCommerce"
                style="max-width: 100%; height: auto; border-radius: 15px; box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);">
        </div>
        
        <p style="margin-top: 20px; font-size: 0.9em; color: var(--color-light-text);">Únete a cientos de emprendedores que ya confían en Massola Group.</p>
    </div>
</section>

<section class="features" id="features-section">
    <div class="container">
        <div class="section-title">
            <h2>Herramientas Poderosas para un Crecimiento Sostenible</h2>
            <p>Desde la gestión de productos hasta la analítica, tienes el control total de tu operación.</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-box"></i></div>
                <h3>Gestión de Inventario Inteligente</h3>
                <p>Control de stock en tiempo real, variantes de productos ilimitadas y alertas de bajo inventario.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-chart-bar"></i></div>
                <h3>Analíticas y Reportes Avanzados</h3>
                <p>Visualiza el rendimiento de tu tienda con gráficos claros y toma decisiones basadas en datos.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-credit-card"></i></div>
                <h3>Procesamiento de Pagos Seguro</h3>
                <p>Integra soluciones de pago robustas y seguras (Stripe) para transacciones sin problemas.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-users-cog"></i></div>
                <h3>Roles y Permisos de Equipo</h3>
                <p>Crea cuentas para tu equipo con permisos limitados. Ideal para delegar tareas de gestión.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                <h3>Hosting y Seguridad Premium</h3>
                <p>Olvídate de servidores. Tu tienda está alojada en infraestructura segura con backups diarios.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-mobile-alt"></i></div>
                <h3>Diseño Responsivo Integrado</h3>
                <p>Todas tus páginas funcionan perfectamente en cualquier dispositivo: móvil, tablet o escritorio.</p>
            </div>
        </div>
    </div>
</section>

<section class="plans" id="plans-section">
    <div class="container">
        <div class="section-title">
            <h2>Elige el Plan que Impulsa tu Emprendimiento</h2>
            <p>Opciones flexibles y transparentes para crecer a tu propio ritmo.</p>
        </div>
        <div class="plans-grid">
            
            <div class="plan-card">
                <div class="plan-name">Básico</div>
                <div class="plan-price">$550 <span>/mes</span></div>
                <ul class="plan-features">
                    <li><i class="fas fa-check-circle"></i> Hasta 20 productos</li>
                    <li><i class="fas fa-check-circle"></i> Tienda Online Personalizable</li>
                    <li><i class="fas fa-check-circle"></i> Gestión de Pedidos Básica</li>
                    <li><i class="fas fa-check-circle"></i> 1 Cuenta de Usuario</li>
                    <li><i class="fas fa-check-circle"></i> Soporte por Email</li>
                </ul>
                <a href="/register.php?plan=basic" class="btn btn-outline" style="margin-top: auto;">Elegir Plan Básico</a>
            </div>
            
            <div class="plan-card featured">
                <div class="plan-name">Profesional</div>
                <div class="plan-price">$750 <span>/mes</span></div>
                <ul class="plan-features">
                    <li><i class="fas fa-check-circle"></i> Productos Ilimitados</li>
                    <li><i class="fas fa-check-circle"></i> Analíticas Avanzadas</li>
                    <li><i class="fas fa-check-circle"></i> Integración con Pagos (Stripe)</li>
                    <li><i class="fas fa-check-circle"></i> Hasta 5 Cuentas de Usuario</li>
                    <li><i class="fas fa-check-circle"></i> Soporte Prioritario 24/7</li>
                    <li><i class="fas fa-check-circle"></i> Roles y Permisos de Equipo</li>
                </ul>
                <a href="/register.php?plan=professional" class="btn btn-primary" style="margin-top: auto;">Comenzar Prueba Pro</a>
            </div>
            
            <div class="plan-card">
                <div class="plan-name">Empresa</div>
                <div class="plan-price">$1500 <span>/mes</span></div>
                <ul class="plan-features">
                    <li><i class="fas fa-check-circle"></i> Todo lo del plan Profesional</li>
                    <li><i class="fas fa-check-circle"></i> Múltiples Tiendas (Multi-tenant)</li>
                    <li><i class="fas fa-check-circle"></i> API y Webhooks de Integración</li>
                    <li><i class="fas fa-check-circle"></i> Cuentas de Usuario Ilimitadas</li>
                    <li><i class="fas fa-check-circle"></i> Consultoría de Estrategia</li>
                </ul>
                <a href="/register.php?plan=enterprise" class="btn btn-outline" style="margin-top: auto;">Elegir Plan Empresa</a>
            </div>
            
        </div>
    </div>
</section>

<section class="cta-section" style="text-align: center;">
    <div class="container" style="background-color: var(--color-white); padding: 50px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
        <h2>Únete a la Revolución del E-commerce.</h2>
        <p style="font-size: 1.2em; margin-bottom: 40px; color: var(--color-light-text);">Empieza hoy mismo y transforma la manera en que haces negocios.</p>
        <a href="/register.php" class="btn btn-primary btn-lg" style="font-size: 1.2em;">¡Crea tu Cuenta Gratis!</a>
    </div>
</section>

<?php include_once __DIR__ . '/footer.php'; ?>
