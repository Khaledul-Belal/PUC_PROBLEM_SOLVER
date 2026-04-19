<?php
// ============================================
// api/track.php — Track page visits
// ============================================
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

$student_id = $input['student_id'] ?? '';
$username   = $input['username'] ?? '';
$page       = $input['page'] ?? '';

if (!$student_id || !$page) {
    jsonResponse(['success' => false]);
}

$db = getDB();
$stmt = $db->prepare("INSERT INTO activity_logs (student_id, username, page_visited) VALUES (?,?,?)");
$stmt->bind_param("sss", $student_id, $username, $page);
$stmt->execute();
$stmt->close();

jsonResponse(['success' => true]);
?>