# MassolaCommerce - Plataforma de Comercio Electrónico

## Instalación Rápida

### Requisitos
- PHP 7.4 o superior
- MySQL 5.7 o superior
- Apache con mod_rewrite
- Extensiones PHP: PDO, mysqli, gd, zip, curl

### Pasos de Instalación

1. **Subir archivos**
   - Sube todos los archivos a tu hosting
   - Asegúrate que las carpetas storage/ tengan permisos 755

2. **Configurar base de datos**
   - Crea una base de datos MySQL
   - Importa el archivo `db/schema.sql`
   - Actualiza los datos en `config.php`

3. **Configurar Stripe (opcional)**
   - Obtén tus claves API de Stripe
   - Actualiza las claves en `config.php`
   - Configura el webhook en Stripe Dashboard

4. **Primer acceso**
   - Accede a tu dominio
   - Usuario admin por defecto: admin
   - IMPORTANTE: Cambia la contraseña inmediatamente

### Estructura de Carpetas

```
/
├── admin/          # Panel de administración
├── assets/         # CSS, JS, imágenes
├── config/         # Configuraciones
├── cron/           # Tareas programadas
├── db/             # Esquemas SQL
├── includes/       # Librerías PHP
├── public/         # Archivos públicos
│   ├── dashboard/  # Panel de tienda
│   └── legal/      # Páginas legales
├── scripts/        # Scripts de mantenimiento
├── storage/        # Archivos subidos y logs
└── themes/         # Temas de tiendas
```

### Configuración de Cron Jobs

Añade estas tareas en tu cPanel:

```bash
# Procesar suscripciones (diario)
0 0 * * * php /home/tu-usuario/public_html/cron/subscriptions.php

# Procesar pagos (cada 15 minutos)
*/15 * * * * php /home/tu-usuario/public_html/cron/payouts_processor.php

# Backup de BD (diario)
0 3 * * * /home/tu-usuario/public_html/scripts/backup_db.sh
```

### Soporte

- Email: soporte@negocios.massolagroup.com
- Documentación: https://docs.massolagroup.com

## Licencia

© 2025 MassolaGroup. Todos los derechos reservados.