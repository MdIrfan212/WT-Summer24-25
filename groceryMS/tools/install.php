<?php
require_once __DIR__ . '/../model/db.php';
require_once __DIR__ . '/../model/data_repo.php';

db()->exec("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin','manager','employee','customer') NOT NULL DEFAULT 'customer',
    status ENUM('active','pending','disabled') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

ensure_tables_exist();

$adminEmail = 'admin@example.com';
$st = db()->prepare('SELECT id FROM users WHERE email=?');
$st->execute([$adminEmail]);
if (!$st->fetch()) {
    $hash = password_hash('admin123', PASSWORD_BCRYPT);
    $ins = db()->prepare('INSERT INTO users(name,email,password_hash,role,status) VALUES(?,?,?,?,?)');
    $ins->execute(['Admin', $adminEmail, $hash, 'admin', 'active']);
    echo "Created default admin: admin@example.com / admin123<br>";
} else {
    echo "Admin already exists.<br>";
}

echo "Tables ready.<br>";
