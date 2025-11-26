# 🔧 LISTA COMPLETA DE ARCHIVOS A MODIFICAR
## MassolaCommerce - Restauración Completa

---

## 📋 RESUMEN EJECUTIVO

**Total de archivos a crear/reemplazar:** 9 archivos  
**Tiempo estimado:** 15-20 minutos  
**Dificultad:** Fácil (solo copiar y pegar archivos)

---

## 🗄️ PASO 1: BASE DE DATOS (5 minutos)

### **Archivo:** `BASE_DATOS_COMPLETA.sql`
**Ubicación:** Ejecutar en phpMyAdmin

### **QUÉ HACE:**
- Crea la base de datos: `massolag_commerce_nat`
- Crea TODAS las tablas necesarias (16 tablas)
- Inserta datos iniciales
- Crea usuario superadmin: `amassola` / `Luyano8906*`

### **CÓMO EJECUTAR:**
```
1. Abrir phpMyAdmin
2. Click en "SQL" en la barra superior
3. Copiar TODO el contenido de BASE_DATOS_COMPLETA.sql
4. Pegar en el editor
5. Click en "Continuar"
6. Esperar confirmación
```

### **TABLAS CREADAS:**
1. `tenants` - Tiendas multi-tenant
2. `users` - Usuarios del sistema
3. `roles` - Roles (superadmin, tenant_admin, vendor, customer)
4. `user_roles` - Asignación de roles
5. `plans` - Planes de suscripción (Básico, Profesional, Empresa)
6. `subscriptions` - Suscripciones activas
7. `products` - Productos de las tiendas
8. `categories` - Categorías de productos
9. `product_categories` - Relación productos-categorías
10. `orders` - Pedidos
11. `order_items` - Items de pedidos
12. `payments` - Pagos
13. `tickets` - Tickets de soporte
14. `ticket_replies` - Respuestas a tickets
15. `tenant_settings` - Configuraciones de tiendas
16. `payouts` - Pagos a vendedores
17. `campaigns` - Campañas de marketing
18. `sessions` - Sesiones de usuarios

---

## 📁 PASO 2: ARCHIVOS EN LA RAÍZ (10 minutos)

### **ARCHIVO 1:** `config.php`
**Desde:** `config_ACTUALIZADO.php`  
**Hacia:** `/home/massolag/public_html/config.php`  
**Acción:** REEMPLAZAR

**QUÉ CAMBIA:**
```php
// ANTES:
'name' => 'massolag_negocios',
'user' => 'massolag_negocios',

// DESPUÉS:
'name' => 'massolag_commerce_nat',
'user' => 'massolag_commerce_nat',
```

---

### **ARCHIVO 2:** `header.php`
**Desde:** `header_CORREGIDO.php`  
**Hacia:** `/home/massolag/public_html/header.php`  
**Acción:** REEMPLAZAR

**QUÉ CAMBIA:**
```html
<!-- ANTES (rutas relativas): -->
<link rel="stylesheet" href="../assets/css/style.css">

<!-- DESPUÉS (rutas absolutas): -->
<link rel="stylesheet" href="/assets/css/style.css">
```

**RUTAS CORREGIDAS:**
- `/assets/css/style.css` ✅
- `/assets/css/main.css` ✅
- `/assets/images/logo-massolagroup.png` ✅
- `/login.php` ✅
- `/register.php` ✅
- `/dashboard.php` ✅
- `/logout.php` ✅

---

### **ARCHIVO 3:** `index.php`
**Desde:** `index.php` (outputs)  
**Hacia:** `/home/massolag/public_html/index.php`  
**Acción:** CREAR/REEMPLAZAR

**QUÉ HACE:**
- Landing page en la raíz
- Rutas absolutas a `/register.php`, `/login.php`
- Imágenes y CSS con rutas absolutas

---

### **ARCHIVO 4:** `login.php`
**Desde:** `login.php` (outputs)  
**Hacia:** `/home/massolag/public_html/login.php`  
**Acción:** CREAR/REEMPLAZAR

**QUÉ HACE:**
- Página de login en la raíz
- Formulario con action="/login.php"
- Redirige a `/dashboard.php` después del login
- CSS inline para que se vea bonito

**FORMULARIO:**
```php
<form method="post" action="/login.php">
```

---

### **ARCHIVO 5:** `register.php`
**Desde:** `register.php` (outputs)  
**Hacia:** `/home/massolag/public_html/register.php`  
**Acción:** CREAR/REEMPLAZAR

**QUÉ HACE:**
- Página de registro en la raíz
- Formulario con action="/register.php"
- Redirige a `/dashboard.php` después del registro
- Validaciones completas

**FORMULARIO:**
```php
<form method="post" action="/register.php">
```

---

### **ARCHIVO 6:** `logout.php`
**Desde:** `logout.php` (outputs)  
**Hacia:** `/home/massolag/public_html/logout.php`  
**Acción:** CREAR

**QUÉ HACE:**
- Cierra sesión
- Destruye cookies
- Redirige a `/login.php`

---

### **ARCHIVO 7:** `dashboard.php`
**Desde:** `dashboard.php` (outputs)  
**Hacia:** `/home/massolag/public_html/dashboard.php`  
**Acción:** CREAR

**QUÉ HACE:**
- Dashboard principal después del login
- Muestra estadísticas
- Enlaces a productos, pedidos, configuración, soporte
- Detecta si el usuario tiene tienda

---

## 🔧 PASO 3: ARCHIVOS QUE NO SE TOCAN

Estos archivos ya están bien y NO necesitas modificarlos:

- ✅ `/includes/init.php`
- ✅ `/includes/db.php`
- ✅ `/includes/functions.php`
- ✅ `/includes/csrf.php`
- ✅ `/includes/mailer.php`
- ✅ `/includes/logger.php`
- ✅ `/footer.php`
- ✅ `/assets/css/style.css`
- ✅ `/assets/css/main.css`
- ✅ `/assets/images/*`

---

## 📝 RESUMEN DE CAMBIOS POR ARCHIVO

### **config.php**
```
CAMBIO: Base de datos massolag_negocios → massolag_commerce_nat
```

### **header.php**
```
CAMBIO: Rutas relativas → Rutas absolutas
../assets/css/style.css → /assets/css/style.css
```

### **index.php** (NUEVO)
```
UBICACIÓN: Raíz en vez de /public/
RUTAS: Todas absolutas desde /
```

### **login.php** (MOVIDO)
```
ANTES: /public/login.php
AHORA: /login.php (raíz)
ACTION: /login.php
REDIRIGE: /dashboard.php
```

### **register.php** (MOVIDO)
```
ANTES: /public/register.php
AHORA: /register.php (raíz)
ACTION: /register.php
REDIRIGE: /dashboard.php
```

### **logout.php** (MOVIDO)
```
ANTES: /public/logout.php
AHORA: /logout.php (raíz)
REDIRIGE: /login.php
```

### **dashboard.php** (NUEVO)
```
UBICACIÓN: Raíz /dashboard.php
PROTEGIDO: Requiere login
MUESTRA: Estadísticas y menú
```

---

## 🎯 FLUJO DE NAVEGACIÓN

```
1. Usuario entra a: https://negocios.massolagroup.com
   └─> Carga: /index.php (landing page)

2. Click en "Iniciar Sesión"
   └─> Va a: /login.php

3. Usuario hace login
   └─> Form POST a: /login.php
   └─> Redirige a: /dashboard.php

4. Usuario navega el dashboard
   ├─> /products.php (gestionar productos)
   ├─> /orders.php (gestionar pedidos)
   ├─> /settings.php (configuración)
   └─> /tickets.php (soporte)

5. Usuario cierra sesión
   └─> Va a: /logout.php
   └─> Redirige a: /login.php
```

---

## ✅ CHECKLIST DE INSTALACIÓN

### **Base de Datos:**
- [ ] Ejecutar BASE_DATOS_COMPLETA.sql en phpMyAdmin
- [ ] Verificar que se crearon 18 tablas
- [ ] Verificar que existe usuario `amassola`
- [ ] Probar login en phpMyAdmin con: massolag_commerce_nat / Luyano8906*

### **Archivos en Raíz:**
- [ ] Reemplazar `/config.php`
- [ ] Reemplazar `/header.php`
- [ ] Crear/Reemplazar `/index.php`
- [ ] Crear/Reemplazar `/login.php`
- [ ] Crear/Reemplazar `/register.php`
- [ ] Crear `/logout.php`
- [ ] Crear `/dashboard.php`

### **Permisos:**
- [ ] `chmod 600 config.php` (proteger)
- [ ] `chmod 777 storage/` (escritura)
- [ ] `chmod 777 storage/logs/` (escritura)

### **Pruebas:**
- [ ] Abrir https://negocios.massolagroup.com
- [ ] Verificar que carga landing page
- [ ] Click en "Iniciar Sesión"
- [ ] Login con: amassola / Luyano8906*
- [ ] Verificar que entra al dashboard
- [ ] Verificar que el CSS carga correctamente
- [ ] Click en "Cerrar Sesión"
- [ ] Verificar que vuelve al login

---

## 📊 ESTRUCTURA FINAL

```
/home/massolag/public_html/
├── index.php              ← NUEVO (landing page)
├── login.php              ← MOVIDO desde /public/
├── register.php           ← MOVIDO desde /public/
├── logout.php             ← NUEVO
├── dashboard.php          ← NUEVO
├── config.php             ← ACTUALIZADO (nueva BD)
├── header.php             ← CORREGIDO (rutas absolutas)
├── footer.php             ← OK (no tocar)
├── .htaccess              ← OK (ya renombrado)
│
├── includes/
│   ├── init.php           ← OK
│   ├── db.php             ← OK
│   ├── functions.php      ← OK
│   ├── csrf.php           ← OK
│   ├── mailer.php         ← OK
│   └── logger.php         ← OK
│
├── assets/
│   ├── css/
│   │   ├── style.css      ← OK
│   │   └── main.css       ← OK
│   ├── js/
│   │   └── main.js        ← OK
│   └── images/
│       └── *.png          ← OK
│
├── storage/
│   ├── logs/              ← Permisos 777
│   ├── cache/             ← Permisos 777
│   └── uploads/           ← Permisos 777
│
└── public/                ← Ahora solo archivos internos
    └── dashboard/
        └── *.php
```

---

## 🔑 CREDENCIALES FINALES

### **Superadmin:**
```
URL: https://negocios.massolagroup.com/login.php
Usuario: amassola
Contraseña: Luyano8906*
Email: amassola@massolagroup.com
```

### **Admin de Tienda Demo:**
```
Usuario: tienda_admin
Contraseña: Demo123!
Email: admin@tienda.com
Tienda: MassolaGroup Store
```

---

## ⏱️ TIEMPO ESTIMADO

| Tarea | Tiempo |
|-------|--------|
| Ejecutar SQL en phpMyAdmin | 5 min |
| Subir/Reemplazar 7 archivos PHP | 10 min |
| Configurar permisos | 3 min |
| Probar login y navegación | 5 min |
| **TOTAL** | **23 min** |

---

## 🆘 SI ALGO FALLA

### **Error: No puedo conectar a la base de datos**
```
Verifica en config.php:
- 'name' => 'massolag_commerce_nat'
- 'user' => 'massolag_commerce_nat'
- 'pass' => 'Luyano8906*'
```

### **Error: CSS no carga**
```
Verifica en header.php:
- Debe decir: /assets/css/style.css (con /)
- NO debe decir: ../assets/css/style.css
```

### **Error: No puedo hacer login**
```
1. Verifica que ejecutaste BASE_DATOS_COMPLETA.sql
2. Prueba en phpMyAdmin:
   SELECT * FROM users WHERE username = 'amassola';
3. Debe existir con password_hash válido
```

### **Error: Página en blanco**
```
1. Revisa error_log en la raíz
2. Verifica que includes/init.php existe
3. Verifica permisos de archivos (644)
```

---

**Creado por:** Claude AI  
**Para:** Iyawo - MassolaGroup  
**Fecha:** 26 de Noviembre, 2025  
**Versión:** 2.0 - Restauración Completa
