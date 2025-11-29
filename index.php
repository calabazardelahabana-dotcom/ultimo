<?php
// index.php - Landing Page
require_once __DIR__ . '/includes/init.php';

$pageTitle = 'MassolaCommerce | Plataforma E-commerce para Emprendedores';
include_once __DIR__ . '/header.php';
?>

<style>
    .hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 80px 20px;
        text-align: center;
    }
    
    .hero h1 {
        font-size: 3em;
        margin-bottom: 20px;
        font-weight: 700;
    }
    
    .hero p {
        font-size: 1.3em;
        margin-bottom: 40px;
        opacity: 0.95;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .hero-buttons {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .hero-buttons .btn {
        font-size: 1.1em;
        padding: 15px 35px;
    }
    
    .features {
        padding: 80px 20px;
        background: white;
    }
    
    .section-title {
        text-align: center;
        margin-bottom: 60px;
    }
    
    .section-title h2 {
        font-size: 2.5em;
        margin-bottom: 15px;
        color: #2d3748;
    }
    
    .section-title p {
        font-size: 1.2em;
        color: #718096;
    }
    
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }
    
    .feature-card {
        padding: 30px;
        background: #f7fafc;
        border-radius: 12px;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .feature-icon {
        font-size: 2.5em;
        color: #667eea;
        margin-bottom: 15px;
    }
    
    .feature-card h3 {
        margin-bottom: 10px;
        color: #2d3748;
    }
    
    .feature-card p {
        color: #718096;
        line-height: 1.7;
    }
    
    .plans {
        padding: 80px 20px;
        background: #f7fafc;
    }
    
    .plans-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 30px;
        max-width: 1100px;
        margin: 0 auto;
    }
    
    .plan-card {
        background: white;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        display: flex;
        flex-direction: column;
        transition: transform 0.3s;
    }
    
    .plan-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    
    .plan-card.featured {
        border: 3px solid #667eea;
        transform: scale(1.05);
    }
    
    .plan-name {
        font-size: 1.5em;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 15px;
    }
    
    .plan-price {
        font-size: 2.5em;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 25px;
    }
    
    .plan-price span {
        font-size: 0.4em;
        color: #718096;
    }
    
    .plan-features {
        list-style: none;
        margin-bottom: 30px;
    }
    
    .plan-features li {
        padding: 10px 0;
        color: #4a5568;
    }
    
    .plan-features i {
        color: #48bb78;
        margin-right: 10px;
    }
    
    .cta-section {
        padding: 80px 20px;
        background: white;
    }
    
    @media (max-width: 768px) {
        .hero h1 {
            font-size: 2em;
        }
        
        .plan-card.featured {
            transform: scale(1);
        }
    }
</style>

<section class="hero">
    <div class="container">
        <h1>Tu Plataforma E-commerce<br>Todo en Uno</h1>
        <p>MassolaCommerce simplifica la gestión de tu negocio. Control total de inventario, ventas y clientes de forma profesional y segura.</p>
        
        <div class="hero-buttons">
            <a href="/register.php" class="btn btn-primary">Comenzar Gratis</a>
            <a href="/login.php" class="btn btn-outline" style="background: white; color: #667eea;">Iniciar Sesión</a>
        </div>
    </div>
</section>

<section class="features" id="features-section">
    <div class="container">
        <div class="section-title">
            <h2>Herramientas Poderosas</h2>
            <p>Todo lo que necesitas para hacer crecer tu negocio</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-box"></i></div>
                <h3>Gestión de Inventario</h3>
                <p>Control de stock en tiempo real, variantes ilimitadas y alertas automáticas.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                <h3>Analíticas Avanzadas</h3>
                <p>Reportes detallados y métricas que impulsan tus decisiones.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-credit-card"></i></div>
                <h3>Pagos Seguros</h3>
                <p>Integración con Stripe para procesar pagos de forma segura.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-users"></i></div>
                <h3>Multi-Usuario</h3>
                <p>Roles y permisos para tu equipo. Colabora sin límites.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                <h3>Seguridad Premium</h3>
                <p>Hosting seguro con backups diarios automáticos.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-mobile-alt"></i></div>
                <h3>100% Responsive</h3>
                <p>Funciona perfectamente en móvil, tablet y escritorio.</p>
            </div>
        </div>
    </div>
</section>

<section class="plans" id="plans-section">
    <div class="container">
        <div class="section-title">
            <h2>Planes Flexibles</h2>
            <p>Elige el plan perfecto para tu negocio</p>
        </div>
        
        <div class="plans-grid">
            <div class="plan-card">
                <div class="plan-name">Básico</div>
                <div class="plan-price">$550 <span>/mes</span></div>
                <ul class="plan-features">
                    <li><i class="fas fa-check-circle"></i> Hasta 20 productos</li>
                    <li><i class="fas fa-check-circle"></i> Tienda Online</li>
                    <li><i class="fas fa-check-circle"></i> Gestión de Pedidos</li>
                    <li><i class="fas fa-check-circle"></i> 1 Usuario</li>
                    <li><i class="fas fa-check-circle"></i> Soporte Email</li>
                </ul>
                <a href="/register.php?plan=basic" class="btn btn-outline" style="margin-top: auto;">Elegir Plan</a>
            </div>
            
            <div class="plan-card featured">
                <div class="plan-name">Profesional</div>
                <div class="plan-price">$750 <span>/mes</span></div>
                <ul class="plan-features">
                    <li><i class="fas fa-check-circle"></i> Productos Ilimitados</li>
                    <li><i class="fas fa-check-circle"></i> Analíticas Avanzadas</li>
                    <li><i class="fas fa-check-circle"></i> Pagos con Stripe</li>
                    <li><i class="fas fa-check-circle"></i> 5 Usuarios</li>
                    <li><i class="fas fa-check-circle"></i> Soporte 24/7</li>
                    <li><i class="fas fa-check-circle"></i> Roles y Permisos</li>
                </ul>
                <a href="/register.php?plan=professional" class="btn btn-primary" style="margin-top: auto;">Comenzar Ahora</a>
            </div>
            
            <div class="plan-card">
                <div class="plan-name">Empresa</div>
                <div class="plan-price">$1500 <span>/mes</span></div>
                <ul class="plan-features">
                    <li><i class="fas fa-check-circle"></i> Todo del Plan Pro</li>
                    <li><i class="fas fa-check-circle"></i> Múltiples Tiendas</li>
                    <li><i class="fas fa-check-circle"></i> API & Webhooks</li>
                    <li><i class="fas fa-check-circle"></i> Usuarios Ilimitados</li>
                    <li><i class="fas fa-check-circle"></i> Consultoría</li>
                </ul>
                <a href="/register.php?plan=enterprise" class="btn btn-outline" style="margin-top: auto;">Contactar</a>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container" style="text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 60px; border-radius: 15px; color: white;">
        <h2 style="font-size: 2.5em; margin-bottom: 20px;">¿Listo para Comenzar?</h2>
        <p style="font-size: 1.3em; margin-bottom: 30px;">Únete a cientos de emprendedores que ya confían en MassolaCommerce</p>
        <a href="/register.php" class="btn btn-primary" style="background: white; color: #667eea; font-size: 1.2em; padding: 15px 40px;">Crear Cuenta Gratis</a>
    </div>
</section>

<?php include_once __DIR__ . '/footer.php'; ?>
