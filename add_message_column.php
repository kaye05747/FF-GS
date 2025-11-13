<?php
require_once __DIR__ . '/../config/db.php';

try {
    $pdo = db();
    $pdo->exec("ALTER TABLE announcements ADD COLUMN message TEXT NOT NULL");
    echo "Table 'announcements' altered successfully.";
} catch (PDOException $e) {
    die("Could not alter table: " . $e->getMessage());
}
?>