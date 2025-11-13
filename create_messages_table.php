<?php
require_once __DIR__ . '/config/db.php';

$pdo = db();

$sql = "
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message_content TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);";

try {
    $pdo->exec($sql);
    echo "Table 'messages' created successfully.\n";
} catch (PDOException $e) {
    die("Error creating messages table: " . $e->getMessage());
}
?>
