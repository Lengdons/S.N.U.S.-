<?php
$stmt = $db->conn->prepare("
    SELECT aktivs, beigu_term
    FROM lietotaji
    WHERE id = ?
");

$stmt->bind_param("i", $_SESSION['lietotajs_id']);
$stmt->execute();

$lietotajsData = $stmt->get_result()->fetch_assoc();

$expired =
    !empty($lietotajsData['beigu_term']) &&
    strtotime($lietotajsData['beigu_term']) < time();

if (
    !$lietotajsData ||
    (int)$lietotajsData['aktivs'] === 0 ||
    $expired
) {

    // auto-disable expired account
    if ($expired) {
        $up = $db->conn->prepare("
            UPDATE lietotaji
            SET aktivs = 0
            WHERE id = ?
        ");
        $up->bind_param("i", $_SESSION['lietotajs_id']);
        $up->execute();
    }

    session_unset();
    session_destroy();

    header("Location: ../index.php?msg=inactive");
    exit;
}
?>