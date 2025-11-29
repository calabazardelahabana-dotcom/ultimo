-- =====================================================
-- BASE DE DATOS COMPLETA - MASSOLACOMMERCE
-- Base de datos: massolag_commerce_nat
-- Usuario superadmin: amassola
-- Contraseña: Luyano8906*
-- =====================================================

DROP DATABASE IF EXISTS massolag_commerce_nat;
CREATE DATABASE massolag_commerce_nat CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE massolag_commerce_nat;

-- =====================================================
-- TABLA: tenants (tiendas multi-tenant)
-- =====================================================
CREATE TABLE tenants (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(191) NOT NULL,
  slug VARCHAR(191) NOT NULL UNIQUE,
  email VARCHAR(191),
  currency CHAR(3) DEFAULT 'USD',
  timezone VARCHAR(100) DEFAULT 'America/Havana',
  payment_provider VARCHAR(50) DEFAULT NULL,
  payment_account_id VARCHAR(191) DEFAULT NULL,
  settings JSON DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL DEFAULT NULL,
  INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: users (usuarios del sistema)
-- =====================================================
CREATE TABLE users (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tenant_id BIGINT UNSIGNED DEFAULT NULL,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(191) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  first_name VARCHAR(100) DEFAULT NULL,
  last_name VARCHAR(100) DEFAULT NULL,
  phone VARCHAR(20) DEFAULT NULL,
  is_active TINYINT(1) DEFAULT 1,
  email_verified TINYINT(1) DEFAULT 0,
  last_login TIMESTAMP NULL DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL DEFAULT NULL,
  INDEX idx_tenant (tenant_id),
  INDEX idx_email (email),
  INDEX idx_username (username),
  FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: roles (roles del sistema)
-- =====================================================
CREATE TABLE roles (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(100) NOT NULL UNIQUE,
  description TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: user_roles (asignación de roles)
-- =====================================================
CREATE TABLE user_roles (
  user_id BIGINT UNSIGNED NOT NULL,
  role_id INT UNSIGNED NOT NULL,
  tenant_id BIGINT UNSIGNED DEFAULT NULL,
  PRIMARY KEY (user_id, role_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
  FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: plans (planes de suscripción)
-- =====================================================
CREATE TABLE plans (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(191) NOT NULL,
  slug VARCHAR(191) NOT NULL UNIQUE,
  description TEXT DEFAULT NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `interval` ENUM('monthly','yearly') DEFAULT 'monthly',
  features JSON DEFAULT NULL,
  max_products INT DEFAULT -1,
  max_users INT DEFAULT -1,
  stripe_price_id VARCHAR(191) DEFAULT NULL,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: subscriptions (suscripciones de tenants)
-- =====================================================
CREATE TABLE subscriptions (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tenant_id BIGINT UNSIGNED NOT NULL,
  plan_id INT UNSIGNED NOT NULL,
  provider_subscription_id VARCHAR(191) DEFAULT NULL,
  status ENUM('active','past_due','cancelled','trialing','expired') DEFAULT 'trialing',
  trial_ends_at TIMESTAMP NULL DEFAULT NULL,
  current_period_start TIMESTAMP NULL,
  current_period_end TIMESTAMP NULL,
  cancel_at TIMESTAMP NULL DEFAULT NULL,
  cancelled_at TIMESTAMP NULL DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
  FOREIGN KEY (plan_id) REFERENCES plans(id) ON DELETE RESTRICT,
  INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: products (productos de las tiendas)
-- =====================================================
CREATE TABLE products (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tenant_id BIGINT UNSIGNED NOT NULL,
  sku VARCHAR(100) DEFAULT NULL,
  title VARCHAR(191) NOT NULL,
  slug VARCHAR(191) NOT NULL,
  description TEXT,
  short_description VARCHAR(500) DEFAULT NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  compare_price DECIMAL(10,2) DEFAULT NULL,
  cost_price DECIMAL(10,2) DEFAULT NULL,
  inventory INT DEFAULT 0,
  track_inventory TINYINT(1) DEFAULT 1,
  allow_backorder TINYINT(1) DEFAULT 0,
  weight DECIMAL(8,2) DEFAULT NULL,
  active TINYINT(1) DEFAULT 1,
  featured TINYINT(1) DEFAULT 0,
  currency CHAR(3) DEFAULT 'USD',
  images JSON DEFAULT NULL,
  metadata JSON DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL DEFAULT NULL,
  FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
  INDEX idx_tenant (tenant_id),
  INDEX idx_slug (slug),
  INDEX idx_active (active),
  FULLTEXT idx_search (title, description)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: categories (categorías de productos)
-- =====================================================
CREATE TABLE categories (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tenant_id BIGINT UNSIGNED NOT NULL,
  parent_id BIGINT UNSIGNED DEFAULT NULL,
  name VARCHAR(191) NOT NULL,
  slug VARCHAR(191) NOT NULL,
  description TEXT DEFAULT NULL,
  image VARCHAR(500) DEFAULT NULL,
  is_active TINYINT(1) DEFAULT 1,
  sort_order INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
  FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
  INDEX idx_tenant (tenant_id),
  INDEX idx_parent (parent_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: product_categories (relación productos-categorías)
-- =====================================================
CREATE TABLE product_categories (
  product_id BIGINT UNSIGNED NOT NULL,
  category_id BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (product_id, category_id),
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: orders (pedidos)
-- =====================================================
CREATE TABLE orders (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tenant_id BIGINT UNSIGNED NOT NULL,
  user_id BIGINT UNSIGNED NULL,
  order_number VARCHAR(50) UNIQUE NOT NULL,
  customer_email VARCHAR(191) NOT NULL,
  customer_name VARCHAR(191) DEFAULT NULL,
  customer_phone VARCHAR(20) DEFAULT NULL,
  subtotal DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  tax DECIMAL(12,2) DEFAULT 0.00,
  shipping DECIMAL(12,2) DEFAULT 0.00,
  discount DECIMAL(12,2) DEFAULT 0.00,
  total DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  currency CHAR(3) DEFAULT 'USD',
  status ENUM('pending','processing','paid','shipped','delivered','cancelled','refunded') DEFAULT 'pending',
  payment_status ENUM('pending','paid','failed','refunded') DEFAULT 'pending',
  payment_method VARCHAR(50) DEFAULT NULL,
  shipping_address JSON DEFAULT NULL,
  billing_address JSON DEFAULT NULL,
  notes TEXT DEFAULT NULL,
  metadata JSON DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_tenant (tenant_id),
  INDEX idx_status (status),
  INDEX idx_order_number (order_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: order_items (items de pedidos)
-- =====================================================
CREATE TABLE order_items (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  order_id BIGINT UNSIGNED NOT NULL,
  product_id BIGINT UNSIGNED DEFAULT NULL,
  product_name VARCHAR(191) NOT NULL,
  product_sku VARCHAR(100) DEFAULT NULL,
  quantity INT UNSIGNED NOT NULL DEFAULT 1,
  unit_price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  total DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  metadata JSON DEFAULT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: payments (pagos)
-- =====================================================
CREATE TABLE payments (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tenant_id BIGINT UNSIGNED DEFAULT NULL,
  order_id BIGINT UNSIGNED NULL,
  transaction_id VARCHAR(191) UNIQUE DEFAULT NULL,
  provider VARCHAR(50) DEFAULT NULL,
  provider_payment_id VARCHAR(191) DEFAULT NULL,
  amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  currency CHAR(3) DEFAULT 'USD',
  status ENUM('pending','processing','succeeded','failed','refunded','cancelled') DEFAULT 'pending',
  payment_method VARCHAR(50) DEFAULT NULL,
  metadata JSON DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL,
  INDEX idx_status (status),
  INDEX idx_transaction (transaction_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: tickets (tickets de soporte)
-- =====================================================
CREATE TABLE tickets (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tenant_id BIGINT UNSIGNED DEFAULT NULL,
  user_id BIGINT UNSIGNED DEFAULT NULL,
  ticket_number VARCHAR(50) UNIQUE NOT NULL,
  type ENUM('soporte','comercial','tecnico','billing') DEFAULT 'soporte',
  subject VARCHAR(255) NOT NULL,
  message TEXT NOT NULL,
  status ENUM('open','in_progress','waiting','resolved','closed') DEFAULT 'open',
  priority ENUM('low','normal','high','urgent') DEFAULT 'normal',
  assigned_to BIGINT UNSIGNED DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  closed_at TIMESTAMP NULL DEFAULT NULL,
  FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_status (status),
  INDEX idx_ticket_number (ticket_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: ticket_replies (respuestas a tickets)
-- =====================================================
CREATE TABLE ticket_replies (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  ticket_id BIGINT UNSIGNED NOT NULL,
  user_id BIGINT UNSIGNED DEFAULT NULL,
  message TEXT NOT NULL,
  is_internal TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: tenant_settings (configuraciones de tiendas)
-- =====================================================
CREATE TABLE tenant_settings (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tenant_id BIGINT UNSIGNED NOT NULL,
  key_name VARCHAR(191) NOT NULL,
  value TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY tenant_key (tenant_id, key_name),
  FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: payouts (pagos a vendedores)
-- =====================================================
CREATE TABLE payouts (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tenant_id BIGINT UNSIGNED DEFAULT NULL,
  order_id BIGINT UNSIGNED DEFAULT NULL,
  requested_amount DECIMAL(12,2) NOT NULL,
  amount_cents INT NOT NULL,
  currency CHAR(3) DEFAULT 'USD',
  stripe_transfer_id VARCHAR(191) DEFAULT NULL,
  stripe_payout_id VARCHAR(191) DEFAULT NULL,
  status ENUM('requested','processing','transferred','paid','failed','cancelled') DEFAULT 'requested',
  notes TEXT DEFAULT NULL,
  metadata JSON DEFAULT NULL,
  requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  processed_at TIMESTAMP NULL DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE SET NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL,
  INDEX idx_tenant (tenant_id),
  INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: campaigns (campañas de marketing)
-- =====================================================
CREATE TABLE campaigns (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tenant_id BIGINT UNSIGNED NOT NULL,
  name VARCHAR(191) NOT NULL,
  slug VARCHAR(191) NOT NULL,
  type ENUM('discount','bundle','promotion') DEFAULT 'discount',
  discount_type ENUM('percentage','fixed') DEFAULT 'percentage',
  discount_value DECIMAL(10,2) DEFAULT 0.00,
  min_purchase DECIMAL(10,2) DEFAULT NULL,
  max_uses INT DEFAULT NULL,
  uses_count INT DEFAULT 0,
  starts_at TIMESTAMP NULL DEFAULT NULL,
  ends_at TIMESTAMP NULL DEFAULT NULL,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
  INDEX idx_tenant (tenant_id),
  INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: sessions (sesiones de usuarios)
-- =====================================================
CREATE TABLE sessions (
  id VARCHAR(128) NOT NULL PRIMARY KEY,
  user_id BIGINT UNSIGNED DEFAULT NULL,
  ip_address VARCHAR(45) DEFAULT NULL,
  user_agent TEXT DEFAULT NULL,
  payload TEXT NOT NULL,
  last_activity INT NOT NULL,
  INDEX idx_user (user_id),
  INDEX idx_last_activity (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- INSERTAR DATOS INICIALES
-- =====================================================

-- Roles del sistema
INSERT INTO roles (name, slug, description) VALUES 
('Superadmin', 'superadmin', 'Administrador supremo de la plataforma'),
('Admin de Tienda', 'tenant_admin', 'Administrador de una tienda específica'),
('Vendedor', 'vendor', 'Vendedor de una tienda'),
('Cliente', 'customer', 'Cliente/comprador');

-- Planes de suscripción
INSERT INTO plans (name, slug, description, price, `interval`, max_products, max_users, features) VALUES
('Básico', 'basic', 'Plan básico para emprendedores', 550.00, 'monthly', 20, 1, 
 JSON_ARRAY('Hasta 20 productos', 'Tienda Online Personalizable', 'Gestión de Pedidos Básica', '1 Cuenta de Usuario', 'Soporte por Email')),
 
('Profesional', 'professional', 'Plan profesional con todas las funciones', 750.00, 'monthly', -1, 5,
 JSON_ARRAY('Productos Ilimitados', 'Analíticas Avanzadas', 'Integración con Pagos (Stripe)', 'Hasta 5 Cuentas de Usuario', 'Soporte Prioritario 24/7', 'Roles y Permisos de Equipo')),
 
('Empresa', 'enterprise', 'Plan empresarial para grandes negocios', 1500.00, 'monthly', -1, -1,
 JSON_ARRAY('Todo lo del plan Profesional', 'Múltiples Tiendas (Multi-tenant)', 'API y Webhooks de Integración', 'Cuentas de Usuario Ilimitadas', 'Consultoría de Estrategia', 'Soporte Dedicado'));

-- Usuario superadmin
-- Contraseña: Luyano8906*
-- Hash generado: $2y$10$e0MYzXyjpJS7Pd0RwLhHOemNYJp.5DGQM4aXo1vFKoZlU.1aJOFNa
INSERT INTO users (username, email, password_hash, first_name, last_name, is_active, email_verified) VALUES
('amassola', 'amassola@massolagroup.com', '$2y$10$e0MYzXyjpJS7Pd0RwLhHOemNYJp.5DGQM4aXo1vFKoZlU.1aJOFNa', 'Admin', 'MassolaGroup', 1, 1);

-- Asignar rol superadmin
INSERT INTO user_roles (user_id, role_id) VALUES (1, 1);

-- Tienda de demostración
INSERT INTO tenants (name, slug, email, currency, timezone) VALUES
('MassolaGroup Store', 'massolagroup', 'store@massolagroup.com', 'USD', 'America/Havana');

-- Usuario admin de tienda demo
-- Usuario: tienda_admin, Contraseña: Demo123!
INSERT INTO users (tenant_id, username, email, password_hash, first_name, last_name, is_active) VALUES
(1, 'tienda_admin', 'admin@tienda.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'Tienda', 1);

INSERT INTO user_roles (user_id, role_id, tenant_id) VALUES (2, 2, 1);

-- Suscripción activa para la tienda demo
INSERT INTO subscriptions (tenant_id, plan_id, status, current_period_start, current_period_end) VALUES
(1, 2, 'active', NOW(), DATE_ADD(NOW(), INTERVAL 1 MONTH));

-- Productos de ejemplo
INSERT INTO products (tenant_id, sku, title, slug, description, price, inventory, active, featured) VALUES
(1, 'PROD-001', 'Producto Demo 1', 'producto-demo-1', 'Este es un producto de demostración para probar la plataforma', 99.99, 100, 1, 1),
(1, 'PROD-002', 'Producto Demo 2', 'producto-demo-2', 'Segundo producto de demostración con características especiales', 149.99, 50, 1, 0),
(1, 'PROD-003', 'Producto Demo 3', 'producto-demo-3', 'Tercer producto de ejemplo con stock limitado', 199.99, 25, 1, 1);

-- =====================================================
-- VERIFICACIÓN
-- =====================================================
SELECT '========================================' AS '';
SELECT 'BASE DE DATOS CREADA EXITOSAMENTE' AS '';
SELECT '========================================' AS '';

SELECT 'USUARIOS CREADOS:' AS '';
SELECT u.id, u.username, u.email, r.name as role, t.name as tienda
FROM users u
LEFT JOIN user_roles ur ON u.id = ur.user_id
LEFT JOIN roles r ON ur.role_id = r.id
LEFT JOIN tenants t ON u.tenant_id = t.id;

SELECT '' AS '';
SELECT 'CREDENCIALES:' AS '';
SELECT '----------------------------' AS '';
SELECT 'SUPERADMIN:' AS '';
SELECT 'Usuario: amassola' AS '';
SELECT 'Contraseña: Luyano8906*' AS '';
SELECT '' AS '';
SELECT 'ADMIN TIENDA:' AS '';
SELECT 'Usuario: tienda_admin' AS '';
SELECT 'Contraseña: Demo123!' AS '';
SELECT '----------------------------' AS '';
