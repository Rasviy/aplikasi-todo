CREATE DATABASE IF NOT EXISTS todo_db;
USE todo_db;

-- Tabel users
CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'ceo', 'manajer', 'pelaksana') NOT NULL
);

-- Tabel tasks
CREATE TABLE tasks (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    assigned_to INT(11),
    status ENUM('pending', 'selesai', 'ditolak', 'tidak dikerjakan') DEFAULT 'pending',
    created_by INT(11),
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);
