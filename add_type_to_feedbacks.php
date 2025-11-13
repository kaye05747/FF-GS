<?php
require_once __DIR__ . '/config/db.php';

$pdo = db();

$sql = "
ALTER TABLE feedbacks
ADD COLUMN type VARCHAR(255) DEFAULT 'General';
";

try {
    $pdo->exec($sql);
    echo "Column 'type' added to table 'feedbacks' successfully.\n";
} catch (PDOException $e) {
    die("Error adding type column to feedbacks table: " . $e->getMessage());
}
?>
