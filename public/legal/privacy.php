<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidad - MassolaGroup</title>
    <link rel="icon" type="image/png" href="/assets/images/logo-massola.png">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            --accent-gradient: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            --trust-gradient: linear-gradient(135deg, #2196F3 0%, #21CBF3 100%);
            --text-dark: #333;
            --text-light: #666;
            --bg-light: #f8f9ff;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding: 40px 0;
        }

        .header h1 {
            color: white;
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .header p {
            color: rgba(255,255,255,0.9);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .content-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .section {
            margin-bottom: 30px;
        }

        .section h2 {
            color: #667eea;
            font-size: 1.4rem;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #f0f0f0;
        }

        .section h3 {
            color: #764ba2;
            font-size: 1.1rem;
            margin-bottom: 10px;
            margin-top: 20px;
        }

        .section p {
            margin-bottom: 12px;
            text-align: justify;
        }

        .section ul {
            margin: 15px 0;
            padding-left: 20px;
        }

        .section li {
            margin-bottom: 8px;
        }

        .highlight-box {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
        }

        .highlight-box h3 {
            color: white;
            margin-bottom: 10px;
        }

        .contact-info {
            background: #f8f9ff;
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #667eea;
        }

        .last-updated {
            background: #e8f2ff;
            color: #667eea;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
            margin-top: 30px;
        }

        .back-button {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 12px 24px;
            border-radius: 25px;
            text-decoration: none;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .back-button:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }

        /* Footer Styles */
        .footer {
            background: var(--text-dark);
            color: white;
            padding: 60px 0 30px;
            margin-top: 50px;
            border-radius: 20px 20px 0 0;
        }

        .footer-links {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-section h4 {
            margin-bottom: 20px;
            color: white;
            font-size: 1.2rem;
        }

        .footer-section a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
            transition: color 0.3s ease;
        }

        .footer-section a:hover {
            color: white;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 20px;
            text-align: center;
            color: rgba(255,255,255,0.5);
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 20px 0;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .content-card {
                padding: 25px;
            }

            .footer-links {
                grid-template-columns: 1fr;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="/" class="back-button">← Volver al Inicio</a>
            <h1>Política de Privacidad</h1>
            <p>Cómo protegemos y utilizamos su información en MassolaGroup Business</p>
        </div>

        <div class="content-card">
            <div class="section">
                <h2>1. Información que Recopilamos</h2>
                <p>En MassolaGroup Business, recopilamos información que usted nos proporciona directamente cuando utiliza nuestros servicios:</p>
                <ul>
                    <li><strong>Información de registro:</strong> Nombre completo, dirección de correo electrónico, número de teléfono</li>
                    <li><strong>Información del negocio:</strong> Nombre comercial, dirección, descripción, datos de contacto</li>
                    <li><strong>Información de pago:</strong> Datos necesarios para procesar suscripciones y transacciones</li>
                    <li><strong>Contenido de la tienda:</strong> Productos, imágenes, descripciones, precios y catálogos</li>
                    <li><strong>Datos de uso:</strong> Cómo interactúa con nuestra plataforma y servicios</li>
                </ul>
            </div>

            <div class="section">
                <h2>2. Uso de la Información</h2>
                <p>Utilizamos la información recopilada para los siguientes propósitos:</p>
                <ul>
                    <li>Proveer, operar y mantener nuestros servicios</li>
                    <li>Procesar pagos y gestionar suscripciones</li>
                    <li>Enviar comunicaciones importantes sobre el servicio</li>
                    <li>Mejorar y personalizar la experiencia del usuario</li>
                    <li>Proporcionar soporte técnico y atención al cliente</li>
                    <li>Cumplir con obligaciones legales y regulatorias</li>
                    <li>Prevenir fraudes y proteger la seguridad de la plataforma</li>
                </ul>
            </div>

            <div class="section">
                <h2>3. Compartición de Información</h2>
                <p>No vendemos, comercializamos ni transferimos su información personal a terceros. Podemos compartir información con:</p>
                
                <h3>Proveedores de Servicios</h3>
                <ul>
                    <li>Proveedores de procesamiento de pagos</li>
                    <li>Servicios de hosting y almacenamiento en la nube</li>
                    <li>Herramientas de análisis y métricas</li>
                    <li>Plataformas de comunicación y soporte</li>
                </ul>

                <h3>Casos Legales</h3>
                <ul>
                    <li>Cumplimiento con requisitos legales o procesos judiciales</li>
                    <li>Protección de nuestros derechos y propiedad</li>
                    <li>Prevención de fraudes o actividades ilegales</li>
                </ul>

                <h3>Transferencias Empresariales</h3>
                <p>En caso de fusión, adquisición o venta de activos, su información podría ser transferida al nuevo propietario.</p>
            </div>

            <div class="section">
                <h2>4. Seguridad de los Datos</h2>
                <p>Implementamos medidas de seguridad robustas para proteger su información:</p>
                <ul>
                    <li>Encriptación SSL/TLS para transmisión de datos</li>
                    <li>Almacenamiento seguro en servidores protegidos</li>
                    <li>Control de acceso y autenticación de usuarios</li>
                    <li>Monitoreo continuo de seguridad</li>
                    <li>Copias de seguridad regulares</li>
                </ul>
                
                <div class="highlight-box">
                    <h3>Su Responsabilidad</h3>
                    <p>Usted es responsable de mantener la confidencialidad de sus credenciales de acceso. Notifíquenos inmediatamente cualquier uso no autorizado de su cuenta.</p>
                </div>
            </div>

            <div class="section">
                <h2>5. Retención de Datos</h2>
                <p>Conservamos su información personal durante el tiempo necesario para:</p>
                <ul>
                    <li>Proveer los servicios solicitados</li>
                    <li>Cumplir con obligaciones legales</li>
                    <li>Resolver disputas y hacer cumplir acuerdos</li>
                    <li>Mantener registros comerciales esenciales</li>
                </ul>
                <p>Puede solicitar la eliminación de sus datos personales en cualquier momento, sujeto a restricciones legales.</p>
            </div>

            <div class="section">
                <h2>6. Sus Derechos de Privacidad</h2>
                <p>Usted tiene derecho a:</p>
                <ul>
                    <li><strong>Acceso:</strong> Solicitar una copia de su información personal</li>
                    <li><strong>Rectificación:</strong> Corregir información inexacta o incompleta</li>
                    <li><strong>Eliminación:</strong> Solicitar la eliminación de sus datos personales</li>
                    <li><strong>Oposición:</strong> Oponerse al procesamiento de sus datos</li>
                    <li><strong>Portabilidad:</strong> Recibir sus datos en formato estructurado</li>
                    <li><strong>Retiro de consentimiento:</strong> Retirar el consentimiento en cualquier momento</li>
                </ul>
                
                <p>Para ejercer estos derechos, contacte a nuestro equipo de privacidad en <strong>negociosonline@massolagroup.com</strong></p>
            </div>

            <div class="section">
                <h2>7. Cookies y Tecnologías de Seguimiento</h2>
                <p>Utilizamos cookies y tecnologías similares para:</p>
                <ul>
                    <li>Mantener sesiones de usuario seguras</li>
                    <li>Recordar preferencias y configuraciones</li>
                    <li>Analizar el uso y rendimiento de la plataforma</li>
                    <li>Mejorar la experiencia del usuario</li>
                </ul>
                <p>Puede gestionar las preferencias de cookies a través de la configuración de su navegador. Consulte nuestra <a href="/cookies/">Política de Cookies</a> para más información.</p>
            </div>

            <div class="section">
                <h2>8. Transferencias Internacionales</h2>
                <p>Sus datos pueden ser procesados y almacenados en servidores ubicados fuera de Cuba. Garantizamos que todas las transferencias internacionales cumplen con los estándares de protección de datos aplicables.</p>
            </div>

            <div class="section">
                <h2>9. Protección de Menores</h2>
                <p>Nuestros servicios no están dirigidos a menores de 18 años. No recopilamos intencionalmente información personal de menores. Si descubre que un menor nos ha proporcionado información personal, contacte inmediatamente a nuestro equipo de privacidad.</p>
            </div>

            <div class="section">
                <h2>10. Cambios a esta Política</h2>
                <p>Podemos actualizar esta Política de Privacidad periódicamente. Le notificaremos sobre cambios significativos mediante:</p>
                <ul>
                    <li>Notificación en la plataforma</li>
                    <li>Correo electrónico a la dirección registrada</li>
                    <li>Publicación en nuestro sitio web</li>
                </ul>
                <p>El uso continuado de nuestros servicios después de los cambios constituye su aceptación de la política revisada.</p>
            </div>

            <div class="section">
                <h2>11. Contacto</h2>
                <p>Para preguntas, consultas o ejercer sus derechos de privacidad, contacte a nuestro equipo:</p>
                
                <div class="contact-info">
                    <h3>Equipo de Privacidad - MassolaGroup Business</h3>
                    <p><strong>Email:</strong> negociosonline@massolagroup.com</p>
                    <p><strong>Teléfono:</strong> +53 5 6697274</p>
                    <p><strong>Sitio web:</strong> https://negocios.massolagroup.com</p>
                    <p><strong>Horario de atención:</strong> Lunes a Viernes, 9:00 AM - 6:00 PM</p>
                </div>
            </div>

            <div class="last-updated">
                Última actualización: Septiembre 2025
            </div>
        </div>

        <!-- Footer Section -->
        <footer class="footer">
            <div class="footer-links">
                <div class="footer-section">
                    <h4>Empresa</h4>
                    <a href="https://massolagroup.com" target="_blank">MassolaGroup.com</a>
                    <a href="mailto:negociosonline@massolagroup.com">Contacto</a>
                    <a href="#blog">Blog</a>
                </div>
                <div class="footer-section">
                    <h4>Legal</h4>
                    <a href="/terminos-servicios/">Términos de Servicios</a>
                    <a href="/privacidad/">Política de Privacidad</a>
                    <a href="/condiciones-uso/">Condiciones de Uso</a>
                    <a href="/cookies/">Política de Cookies</a>
                </div>
                <div class="footer-section">
                    <h4>Contacto</h4>
                    <a href="mailto:negociosonline@massolagroup.com">negociosonline@massolagroup.com</a>
                    <a href="tel:+5356697274">+53 5 6697274</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 MassolaGroup. Todos los derechos reservados.</p>
            </div>
        </footer>
    </div>
</body>
</html>