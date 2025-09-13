<?php
require_once __DIR__ . '/db.php';

function ensure_tables_exist() {
    $sqls = [
        "CREATE TABLE IF NOT EXISTS products (
            id INT PRIMARY KEY,
            name VARCHAR(200) NOT NULL,
            category VARCHAR(100) NOT NULL,
            subcategory VARCHAR(100) DEFAULT NULL,
            price DECIMAL(10,2) NOT NULL DEFAULT 0,
            stock INT NOT NULL DEFAULT 0,
            image VARCHAR(255) DEFAULT 'placeholder.png'
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
        "CREATE TABLE IF NOT EXISTS orders (
            id VARCHAR(32) PRIMARY KEY,
            product_id VARCHAR(32) NOT NULL,
            product_name VARCHAR(200) NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            qty INT NOT NULL,
            total DECIMAL(10,2) NOT NULL,
            `by` VARCHAR(200) DEFAULT NULL,
            paid TINYINT(1) NOT NULL DEFAULT 0,
            method VARCHAR(50) DEFAULT NULL,
            status VARCHAR(50) DEFAULT 'Pending',
            date DATETIME NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
        "CREATE TABLE IF NOT EXISTS discounts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(64) UNIQUE,
            percent INT NOT NULL DEFAULT 0,
            active TINYINT(1) NOT NULL DEFAULT 1
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
        "CREATE TABLE IF NOT EXISTS activity_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            action VARCHAR(255) NOT NULL,
            user VARCHAR(200) DEFAULT NULL,
            date DATETIME NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    ];
    foreach ($sqls as $sql) { db()->exec($sql); }
}

function load_json($file) {
    ensure_tables_exist();
    $base = basename($file, '.json');
    switch ($base) {
        case 'products':
            $rows = db()->query('SELECT id,name,category,subcategory,price,stock,image FROM products ORDER BY id')->fetchAll();
            return $rows ?: [];
        case 'orders':
            $rows = db()->query('SELECT id,product_id,product_name,price,qty,total,`by`,paid,method,status,date FROM orders ORDER BY date DESC')->fetchAll();
            return $rows ?: [];
        case 'discounts':
            $rows = db()->query('SELECT id,code,percent,active FROM discounts ORDER BY id')->fetchAll();
            return $rows ?: [];
        case 'activity_logs':
            $rows = db()->query('SELECT id,action,user,date FROM activity_logs ORDER BY id DESC')->fetchAll();
            return $rows ?: [];
        default:
            return [];
    }
}

function save_json($file, $data) {
    ensure_tables_exist();
    $base = basename($file, '.json');
    $pdo = db();
    $pdo->beginTransaction();
    try {
        switch ($base) {
            case 'products':
                $pdo->exec('DELETE FROM products');
                $ins = $pdo->prepare('INSERT INTO products(id,name,category,subcategory,price,stock,image) VALUES(?,?,?,?,?,?,?)');
                foreach ($data as $r) {
                    $ins->execute([(int)($r['id'] ?? 0),(string)($r['name'] ?? ''),(string)($r['category'] ?? ''),(string)($r['subcategory'] ?? null),(float)($r['price'] ?? 0),(int)($r['stock'] ?? 0),(string)($r['image'] ?? 'placeholder.png')]);
                }
                break;
            case 'orders':
                $pdo->exec('DELETE FROM orders');
                $ins = $pdo->prepare('INSERT INTO orders(id,product_id,product_name,price,qty,total,`by`,paid,method,status,date) VALUES(?,?,?,?,?,?,?,?,?,?,?)');
                foreach ($data as $r) {
                    $ins->execute([(string)($r['id'] ?? ''),(string)($r['product_id'] ?? ''),(string)($r['product_name'] ?? ''),(float)($r['price'] ?? 0),(int)($r['qty'] ?? 0),(float)($r['total'] ?? 0),(string)($r['by'] ?? null),(int)($r['paid'] ?? 0),(string)($r['method'] ?? null),(string)($r['status'] ?? 'Pending'),(string)($r['date'] ?? date('Y-m-d H:i:s'))]);
                }
                break;
            case 'discounts':
                $pdo->exec('DELETE FROM discounts');
                $ins = $pdo->prepare('INSERT INTO discounts(id,code,percent,active) VALUES(?,?,?,?)');
                foreach ($data as $r) {
                    $ins->execute([isset($r['id']) ? (int)$r['id'] : null,(string)($r['code'] ?? ''),(int)($r['percent'] ?? 0),(int)($r['active'] ?? 1)]);
                }
                break;
            case 'activity_logs':
                $pdo->exec('DELETE FROM activity_logs');
                $ins = $pdo->prepare('INSERT INTO activity_logs(id,action,user,date) VALUES(?,?,?,?)');
                foreach ($data as $r) {
                    $ins->execute([isset($r['id']) ? (int)$r['id'] : null,(string)($r['action'] ?? ''),(string)($r['user'] ?? null),(string)($r['date'] ?? date('Y-m-d H:i:s'))]);
                }
                break;
            default:
                break;
        }
        $pdo->commit();
        return true;
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}