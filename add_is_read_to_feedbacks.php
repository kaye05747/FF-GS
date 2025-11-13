<?php
require_once __DIR__ . '/config/db.php';

try {
    $pdo = db();
    $sql = "ALTER TABLE feedbacks ADD COLUMN is_read BOOLEAN NOT NULL DEFAULT 0";
    $pdo->exec($sql);
    echo "Column 'is_read' added to 'feedbacks' table successfully.";
} catch (PDOException $e) {
        $dbname = 'farmer_feedback'; // your database name
    die("Could not connect to the database $dbname :" . $e->getMessage());
}
?>