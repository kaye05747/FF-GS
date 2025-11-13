<?php
require_once __DIR__ . '/../config/db.php';

try {
    $pdo = db();
    $pdo->exec("CREATE TABLE announcements (
        id INT AUTO_INCREMENT PRIMARY KEY,
        announcement TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "Table 'announcements' created successfully.";
} catch (PDOException $e) {
    die("Could not create table: " . $e->getMessage());
}
?>