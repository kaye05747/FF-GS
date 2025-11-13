<?php
require_once __DIR__ . '/../config/db.php';

try {
    $pdo = db();
    $pdo->exec("DROP TABLE IF EXISTS announcements");
    $pdo->exec("CREATE TABLE announcements (
        id INT AUTO_INCREMENT PRIMARY KEY,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "Table 'announcements' created successfully.";
} catch (PDOException $e) {
    die("Could not create table: " . $e->getMessage());
}
?>