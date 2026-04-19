<?php
// ============================================
// api/profile.php — Update Profile
// ============================================
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
$action = $input['action'] ?? '';

$db = getDB();

// ---- UPDATE PROFILE ----
if ($action === 'update') {
    $student_id = $input['student_id'] ?? '';
    $department = $input['department'] ?? '';
    $semester   = $input['semester'] ?? '';
    $section    = $input['section'] ?? '';
    $blood      = $input['blood_group'] ?? '';
    $advisor    = $input['advisor'] ?? '';
    $img        = $input['profile_image'] ?? '';

    $stmt = $db->prepare(
        "UPDATE users SET department=?, semester=?, section=?, blood_group=?, advisor=?, profile_image=?, profile_completed=1 WHERE student_id=?"
    );
    $stmt->bind_param("sssssss", $department, $semester, $section, $blood, $advisor, $img, $student_id);
    $stmt->execute();
    $stmt->close();

    jsonResponse(['success' => true, 'message' => 'Profile updated!']);
}

// ---- GET PROFILE ----
if ($action === 'get') {
    $student_id = $input['student_id'] ?? '';
    $stmt = $db->prepare("SELECT * FROM users WHERE student_id = ? LIMIT 1");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if ($user) { unset($user['password_hash']); }
    jsonResponse(['success' => (bool)$user, 'user' => $user]);
}

jsonResponse(['success' => false, 'message' => 'Unknown action']);
?>