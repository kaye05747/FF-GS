<?php
require_once __DIR__ . '/includes/functions.php';
require_login();
$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedback_id = intval($_POST['feedback_id'] ?? 0);
    $rating = intval($_POST['rating'] ?? 0);
    $user_id = $_SESSION['user']['id'];

    if ($rating >= 1 && $rating <= 5) {
        // Check if the user has already rated this feedback
        $stmt = $pdo->prepare("SELECT id FROM feedback_ratings WHERE user_id = ? AND feedback_id = ?");
        $stmt->execute([$user_id, $feedback_id]);
        $existing_rating = $stmt->fetch();

        if ($existing_rating) {
            // User has already rated, so update the rating
            $stmt = $pdo->prepare("UPDATE feedback_ratings SET rating = ? WHERE id = ?");
            $stmt->execute([$rating, $existing_rating['id']]);
        } else {
            // User has not rated it yet, so insert a new rating
            $stmt = $pdo->prepare("INSERT INTO feedback_ratings (user_id, feedback_id, rating) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $feedback_id, $rating]);
        }
    }

    // Get the new average rating
    $stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as rating_count FROM feedback_ratings WHERE feedback_id = ?");
    $stmt->execute([$feedback_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'avg_rating' => number_format($result['avg_rating'] ?? 0, 1),
        'rating_count' => $result['rating_count'] ?? 0
    ]);
}
