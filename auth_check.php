<?php
$stmt = $db->conn->prepare("
    SELECT is_active, expires_at
    FROM users
    WHERE id = ?
");

$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();

$userData = $stmt->get_result()->fetch_assoc();

$expired =
    !empty($userData['expires_at']) &&
    strtotime($userData['expires_at']) < time();

if (
    !$userData ||
    (int)$userData['is_active'] === 0 ||
    $expired
) {

    // optional: auto-disable expired account
    if ($expired) {
        $up = $db->conn->prepare("
            UPDATE users
            SET is_active = 0
            WHERE id = ?
        ");
        $up->bind_param("i", $_SESSION['user_id']);
        $up->execute();
    }

    session_unset();
    session_destroy();

    header("Location: index.php?msg=inactive");
    exit;
}
?>