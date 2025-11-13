<?php
require_once __DIR__ . '/config/db.php';

try {
    $pdo = db();
    
    $sql = "
    CREATE TABLE IF NOT EXISTS notifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        message VARCHAR(255) NOT NULL,
        is_read BOOLEAN NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    );
    ";
    
    $pdo->exec($sql);
    
    echo "Table 'notifications' created successfully.";
    
} catch (PDOException $e) {
    die("Could not create table: " . $e->getMessage());
}
?>