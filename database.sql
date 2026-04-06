-- ========================================================
-- SESMT System Database Script
-- Saúde e Segurança do Trabalho Management System
-- MySQL Database and Tables
-- ========================================================

-- Create Database
CREATE DATABASE IF NOT EXISTS sesmt_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sesmt_db;

-- ========================================================
-- USERS TABLE - Sistema de autenticação
-- ========================================================
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'comum') DEFAULT 'comum',
    active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_active (active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- EMPLOYEES TABLE - Cadastro de colaboradores
-- ========================================================
CREATE TABLE IF NOT EXISTS employees (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    cpf VARCHAR(14) UNIQUE NOT NULL,
    email VARCHAR(255),
    job_title VARCHAR(255) NOT NULL,
    department VARCHAR(255) NOT NULL,
    hire_date DATE NOT NULL,
    phone VARCHAR(20),
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_cpf (cpf),
    INDEX idx_department (department),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- WORK_PERMITS TABLE - Permissão de Trabalho (PT)
-- ========================================================
CREATE TABLE IF NOT EXISTS work_permits (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    type ENUM('altura', 'eletricidade', 'espaco_confinado', 'trabalho_quente') NOT NULL,
    department VARCHAR(255),
    description TEXT,
    issue_date DATE NOT NULL,
    expiry_date DATE,
    validated_by VARCHAR(255),
    status ENUM('ativa', 'expirada', 'cancelada') DEFAULT 'ativa',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    INDEX idx_employee_id (employee_id),
    INDEX idx_type (type),
    INDEX idx_status (status),
    INDEX idx_expiry_date (expiry_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- EPIS TABLE - Equipamentos de Proteção Individual
-- ========================================================
CREATE TABLE IF NOT EXISTS epis (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(255) NOT NULL,
    description TEXT,
    expiry_date DATE,
    quantity INT DEFAULT 0,
    unit_cost DECIMAL(10, 2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_type (type),
    INDEX idx_expiry_date (expiry_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- EPI_DELIVERIES TABLE - Histórico de entrega de EPIs
-- ========================================================
CREATE TABLE IF NOT EXISTS epi_deliveries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    epi_id INT NOT NULL,
    employee_id INT NOT NULL,
    delivery_date DATE NOT NULL,
    quantity INT DEFAULT 1,
    delivery_condition ENUM('novo', 'usado', 'danificado') DEFAULT 'novo',
    expiry_date DATE,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (epi_id) REFERENCES epis(id) ON DELETE CASCADE,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    INDEX idx_epi_id (epi_id),
    INDEX idx_employee_id (employee_id),
    INDEX idx_delivery_date (delivery_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- RISKS TABLE - Gestão de Riscos (PGR/GRO)
-- ========================================================
CREATE TABLE IF NOT EXISTS risks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    description TEXT NOT NULL,
    level ENUM('baixo', 'medio', 'alto') NOT NULL,
    department VARCHAR(255),
    control_measures TEXT,
    responsible_person VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_level (level),
    INDEX idx_department (department)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- ACCIDENTS TABLE - Registro de Acidentes
-- ========================================================
CREATE TABLE IF NOT EXISTS accidents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    accident_date DATE NOT NULL,
    accident_time TIME,
    description TEXT NOT NULL,
    injury_type VARCHAR(255),
    body_part VARCHAR(255),
    status ENUM('aberto', 'investigado', 'fechado') DEFAULT 'aberto',
    action_plan TEXT,
    investigation_responsible VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    INDEX idx_employee_id (employee_id),
    INDEX idx_accident_date (accident_date),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- TRAININGS TABLE - Treinamentos Obrigatórios
-- ========================================================
CREATE TABLE IF NOT EXISTS trainings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    is_mandatory TINYINT(1) DEFAULT 1,
    duration_hours INT,
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- TRAINING_EMPLOYEES TABLE - Controle de treina de colaboradores
-- ========================================================
CREATE TABLE IF NOT EXISTS training_employees (
    id INT PRIMARY KEY AUTO_INCREMENT,
    training_id INT NOT NULL,
    employee_id INT NOT NULL,
    completion_date DATE,
    expiry_date DATE,
    status ENUM('pendente', 'concluido', 'vencido', 'cancelado') DEFAULT 'pendente',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (training_id) REFERENCES trainings(id) ON DELETE CASCADE,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    UNIQUE KEY unique_training_employee (training_id, employee_id),
    INDEX idx_training_id (training_id),
    INDEX idx_employee_id (employee_id),
    INDEX idx_status (status),
    INDEX idx_expiry_date (expiry_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- SETTINGS TABLE - Cadastros dinâmicos de tipos e status
-- ========================================================
CREATE TABLE IF NOT EXISTS settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type VARCHAR(50) NOT NULL,
    code VARCHAR(100) NOT NULL,
    label VARCHAR(255) NOT NULL,
    active TINYINT(1) DEFAULT 1,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_setting (type, code),
    INDEX idx_type (type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- INSERT DEFAULT DATA
-- ========================================================

-- Default admin user: admin@sesmt.com / admin123
INSERT INTO users (name, email, password, role, active) VALUES 
('Administrador SESMT', 'admin@sesmt.com', '$2y$10$4K6TZrRvJMvQQw.QS7F6Qu.3y1j.TUJ.5Jl5D.0V1F8K5gV.jQmba', 'admin', 1);

-- Default dynamic config values
INSERT INTO settings (type, code, label, active, sort_order) VALUES
('pt_type', 'altura', 'Trabalho em Altura', 1, 1),
('pt_type', 'eletricidade', 'Trabalho com Eletricidade', 1, 2),
('pt_type', 'espaco_confinado', 'Espaço Confinado', 1, 3),
('pt_type', 'trabalho_quente', 'Trabalho a Quente', 1, 4),
('accident_status', 'aberto', 'Aberto', 1, 1),
('accident_status', 'investigado', 'Investigado', 1, 2),
('accident_status', 'fechado', 'Fechado', 1, 3),
('training_status', 'pendente', 'Pendente', 1, 1),
('training_status', 'concluido', 'Concluído', 1, 2),
('training_status', 'vencido', 'Vencido', 1, 3),
('training_status', 'cancelado', 'Cancelado', 1, 4),
('epi_condition', 'novo', 'Novo', 1, 1),
('epi_condition', 'usado', 'Usado', 1, 2),
('epi_condition', 'danificado', 'Danificado', 1, 3),
('employee_status', 'ativo', 'Ativo', 1, 1),
('employee_status', 'inativo', 'Inativo', 1, 2);

-- Sample Employee Data


-- Sample EPI Data
INSERT INTO epis (name, type, description, expiry_date, quantity, unit_cost) VALUES
('Capacete Segurança', 'Proteção Cabeça', 'Capacete amarelo com ABS', '2025-12-31', 50, 0),
('Luvas Nitrílicas', 'Proteção Mãos', 'Luvas resistentes a químicos', '2025-06-30', 200, 0),
('Óculos Proteção', 'Proteção Olhos', 'Óculos anti-reflexo', '2025-10-31', 80, 0),
('Colete Segurança', 'Proteção Corpo', 'Colete refletivo', '2026-03-31', 40, 0),
('Bota Segurança', 'Proteção Pés', 'Bota com biqueira aço', '2025-08-31', 60, 0);

-- Sample Risk Data
INSERT INTO risks (description, level, department, control_measures, responsible_person) VALUES
('Queda de altura', 'alto', 'Manutenção', 'Uso obrigatório de cinto de segurança e proteção contra queda', 'Carlos Ferreira'),
('Choque elétrico', 'alto', 'Manutenção', 'Equipamento isolado e NR-10 obrigatória', 'Maria Santos'),
('Queimaduras', 'medio', 'Produção', 'Uso de EPI adequado e procedimentos de trabalho', 'João Silva'),
('Ferimentos em maquinário', 'alto', 'Produção', 'Travamento de máquinas e proteção de pontos moveis', 'Carlos Ferreira'),
('Exposição química', 'medio', 'Produção', 'Ventilação e uso de luvas, óculos', 'Maria Santos');

-- Sample Training Data
INSERT INTO trainings (name, description, is_mandatory, duration_hours, content) VALUES
('Segurança Geral no Trabalho', 'Treinamento básico de segurança', 1, 8, 'Normas gerais de segurança, EPI, acidentes'),
('NR-10 - Eletricidade', 'Trabalhos com energia elétrica', 1, 40, 'Norma reguladora de eletricidade'),
('Trabalho em Altura', 'Procedimentos para trabalho acima de 2 metros', 1, 20, 'Proteção contra queda, cinto segurança'),
('Espaço Confinado', 'Procedimentos em espaço confinado', 1, 16, 'Isolamento, ventilação, monitoramento'),
('Trabalho a Quente', 'Procedimentos para trabalho com calor', 0, 8, 'Proteção térmica, EPI específico');

-- ========================================================
-- CREATE INDEXES
-- ========================================================

-- Additional useful indexes for performance
CREATE INDEX idx_users_email_active ON users(email, active);
CREATE INDEX idx_employees_hire_date ON employees(hire_date);
CREATE INDEX idx_accidents_date_status ON accidents(accident_date, status);
CREATE INDEX idx_trainings_mandatory ON trainings(is_mandatory);

-- ========================================================
-- END OF DATABASE SCRIPT
-- ========================================================
