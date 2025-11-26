-- MassolaCommerce Database Schema
-- Version: 1.0.0

CREATE DATABASE IF NOT EXISTS `massolag_negocios` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `massolag_negocios`;

-- Tabla: tenants (tiendas)
CREATE TABLE IF NOT EXISTS tenants (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(191) NOT NULL,
  slug VARCHAR(191) NOT NULL UNIQUE,
  email VARCHAR(191),
  currency CHAR(3) DEFAULT 'USD',
  timezone VARCHAR(100) DEFAULT 'UTC',
  payment_provider VARCHAR(50) DEFAULT NULL,
  payment_account_id VARCHAR(191) DEFAULT NULL,
  settings JSON DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  deleted_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla: users (usuarios)
CREATE TABLE IF NOT EXISTS users (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tenant_id BIGINT UNSIGNED DEFAULT NULL,
  username VARCHAR(100) DEFAULT NULL,
  email VARCHAR(191) DEFAULT NULL,
  password_hash VARCHAR(255) DEFAULT NULL,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  deleted_at TIMESTAMP NULL DEFAULT NULL,
  INDEX (tenant_id),
  UNIQUE KEY username_unique (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla: roles
CREATE TABLE IF NOT EXISTS roles (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla: user_roles
CREATE TABLE IF NOT EXISTS user_roles (
  user_id BIGINT UNSIGNED NOT NULL,
  role_id INT UNSIGNED NOT NULL,
  tenant_id BIGINT UNSIGNED DEFAULT NULL,
  PRIMARY KEY (user_id, role_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla: plans
CREATE TABLE IF NOT EXISTS plans (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(191) NOT NULL,
  slug VARCHAR(191) NOT NULL UNIQUE,
  price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `interval` ENUM('monthly','yearly') DEFAULT 'monthly',
  features JSON DEFAULT NULL,
  stripe_price_id VARCHAR(191) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla: subscriptions
CREATE TABLE IF NOT EXISTS subscriptions (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tenant_id BIGINT UNSIGNED NOT NULL,
  plan_id INT UNSIGNED NOT NULL,
  provider_subscription_id VARCHAR(191) DEFAULT NULL,
  status ENUM('active','past_due','cancelled','trialing') DEFAULT 'trialing',
  current_period_start TIMESTAMP NULL,
  current_period_end TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
  FOREIGN KEY (plan_id) REFERENCES plans(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla: products
CREATE TABLE IF NOT EXISTS products (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tenant_id BIGINT UNSIGNED NOT NULL,
  sku VARCHAR(100) DEFAULT NULL,
  title VARCHAR(191) NOT NULL,
  slug VARCHAR(191) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  inventory INT DEFAULT 0,
  active TINYINT(1) DEFAULT 1,
  currency CHAR(3) DEFAULT 'USD',
  metadata JSON DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
  INDEX (tenant_id),
  INDEX (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla: orders
CREATE TABLE IF NOT EXISTS orders (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tenant_id BIGINT UNSIGNED NOT NULL,
  user_id BIGINT UNSIGNED NULL,
  total DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  currency CHAR(3) DEFAULT 'USD',
  status ENUM('pending','paid','shipped','cancelled','refunded') DEFAULT 'pending',
  shipping_address JSON DEFAULT NULL,
  billing_address JSON DEFAULT NULL,
  metadata JSON DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
  INDEX (tenant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla: order_items
CREATE TABLE IF NOT EXISTS order_items (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  order_id BIGINT UNSIGNED NOT NULL,
  product_id BIGINT UNSIGNED NOT NULL,
  quantity INT UNSIGNED NOT NULL DEFAULT 1,
  unit_price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  total DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla: payments
CREATE TABLE IF NOT EXISTS payments (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tenant_id BIGINT UNSIGNED DEFAULT NULL,
  order_id BIGINT UNSIGNED NULL,
  provider VARCHAR(50) DEFAULT NULL,
  provider_payment_id VARCHAR(191) DEFAULT NULL,
  amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  currency CHAR(3) DEFAULT 'USD',
  status ENUM('pending','succeeded','failed','refunded') DEFAULT 'pending',
  metadata JSON DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla: tickets
CREATE TABLE IF NOT EXISTS tickets (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tenant_id BIGINT UNSIGNED DEFAULT NULL,
  user_id BIGINT UNSIGNED DEFAULT NULL,
  type ENUM('soporte','comercial') DEFAULT 'soporte',
  subject VARCHAR(255),
  message TEXT,
  status ENUM('open','in_progress','closed') DEFAULT 'open',
  priority ENUM('low','normal','high') DEFAULT 'normal',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla: tenant_settings
CREATE TABLE IF NOT EXISTS tenant_settings (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tenant_id BIGINT UNSIGNED NOT NULL,
  key_name VARCHAR(191) NOT NULL,
  value TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  UNIQUE KEY tenant_key (tenant_id, key_name),
  FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla: payouts
CREATE TABLE IF NOT EXISTS payouts (
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
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  INDEX (tenant_id),
  INDEX (order_id),
  INDEX (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar datos iniciales
INSERT INTO roles (name, slug) VALUES 
('Superadmin', 'superadmin'),
('Tenant Admin', 'tenant_admin'),
('Customer', 'customer');

INSERT INTO plans (name, slug, price, `interval`) VALUES
('Básico', 'basic', 9.99, 'monthly'),
('Profesional', 'professional', 29.99, 'monthly'),
('Empresa', 'enterprise', 99.99, 'monthly');

-- Crear usuario admin por defecto (cambiar contraseña después)
INSERT INTO users (username, email, password_hash, is_active) VALUES
('admin', 'admin@massolagroup.com', '$2y$10$YourHashHere', 1);

-- Asignar rol superadmin al usuario admin
INSERT INTO user_roles (user_id, role_id) VALUES (1, 1);