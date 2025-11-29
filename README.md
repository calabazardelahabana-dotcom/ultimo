# MassolaCommerce - Plataforma E-commerce

## Instalación

### 1. Subir archivos
Sube todos los archivos a la raíz de tu dominio: `negocios.massolagroup.com`

### 2. Configurar Base de Datos

#### Opción A: Crear manualmente desde cPanel
1. Accede a **cPanel > MySQL Databases**
2. Crea una base de datos: `massolag_negocios`
3. Crea un usuario: `massolag_negocios`
4. Asigna el usuario a la BD con **TODOS** los privilegios
5. Ve a **phpMyAdmin** e importa el archivo `db/schema.sql`

#### Opción B: Usar el instalador automático
1. Accede a: `https://negocios.massolagroup.com/install.php`
2. Sigue las instrucciones en pantalla

### 3. Verificar configuración
Edita `config.php` y verifica:
```php
'host' => 'localhost',  // o la IP de tu servidor MySQL
'name' => 'massolag_negocios',
'user' => 'massolag_negocios',
'pass' => 'tu_contraseña_aqui'
```

### 4. Configurar permisos
```bash
chmod 755 storage/
chmod 755 storage/logs/
chmod 755 storage/uploads/
chmod 755 storage/cache/
```

### 5. Acceder a la plataforma
- **Landing Page**: https://negocios.massolagroup.com/
- **Login**: https://negocios.massolagroup.com/login.php
- **Registro**: https://negocios.massolagroup.com/register.php

### Usuarios por defecto (si importaste schema.sql)
```
SUPERADMIN:
Usuario: amassola
Contraseña: Luyano8906*

ADMIN TIENDA:
Usuario: tienda_admin
Contraseña: Demo123!
```

## Estructura de archivos

```
/
├── config.php              # Configuración principal
├── index.php              # Landing page
├── login.php              # Inicio de sesión
├── register.php           # Registro
├── dashboard.php          # Panel principal
├── logout.php             # Cerrar sesión
├── header.php             # Header global
├── footer.php             # Footer global
├── .htaccess             # Configuración Apache
├── includes/             # Archivos core
│   ├── init.php         # Inicializador
│   ├── db.php           # Conexión BD
│   ├── functions.php    # Funciones comunes
│   └── csrf.php         # Protección CSRF
├── assets/              # Recursos estáticos
│   ├── css/
│   ├── js/
│   └── images/
├── storage/             # Almacenamiento
│   ├── logs/
│   ├── uploads/
│   └── cache/
├── db/                  # Base de datos
│   └── schema.sql       # Estructura completa
└── admin/              # Panel administrativo
```

## Solución de problemas

### Error: "No se puede conectar a la base de datos"
1. Verifica las credenciales en `config.php`
2. Asegúrate de que la BD existe en MySQL
3. Verifica que el usuario tiene permisos en la BD
4. Cambia `host` de '127.0.0.1' a 'localhost' (o viceversa)

### Error 500
1. Revisa el archivo `error_log` en la raíz
2. Verifica permisos de carpetas (755 para directorios, 644 para archivos)
3. Asegúrate de que PHP >= 7.4 esté instalado

### Páginas en blanco
1. Activa `display_errors` temporalmente en `.htaccess`
2. Revisa `storage/logs/` para errores
3. Verifica que todos los archivos se subieron correctamente

## Soporte
- Email: soporte@massolagroup.com
- Comercial: comercial@massolagroup.com

## Licencia
© 2025 MassolaGroup. Todos los derechos reservados.
