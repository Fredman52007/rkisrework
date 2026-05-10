-- Таблица отделов
CREATE TABLE departments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    manager_name VARCHAR(255)
);

-- Таблица сотрудников и их данных в справочнике
CREATE TABLE employees (
    id INT PRIMARY KEY AUTO_INCREMENT,
    last_name VARCHAR(100) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    phone VARCHAR(20) NOT NULL,
    position VARCHAR(150),
    room_number VARCHAR(10),
    dept_id INT,
    FOREIGN KEY (dept_id) REFERENCES departments(id)
);

-- Таблица аккаунтов (для авторизации)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'employee') DEFAULT 'employee',
    can_search BOOLEAN DEFAULT FALSE
);