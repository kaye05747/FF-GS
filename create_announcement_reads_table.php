<?php
require_once __DIR__ . '/config/db.php';

try {
    $pdo = db();
    $sql = "CREATE TABLE announcement_reads (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        announcement_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (announcement_id) REFERENCES announcements(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);
    echo "Table 'announcement_reads' created successfully.";
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
?>