<?php
require_once __DIR__ . '/config/db.php';

$pdo = db();

$sql = "
ALTER TABLE feedback_likes RENAME TO feedback_ratings;
ALTER TABLE feedback_ratings ADD COLUMN rating INT NOT NULL AFTER feedback_id;
";

$pdo->exec($sql);

echo "Table 'feedback_likes' renamed to 'feedback_ratings' and 'rating' column added.";


