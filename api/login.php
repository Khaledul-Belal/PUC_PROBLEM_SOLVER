<?php
// ============================================
// api/login.php — Login Handler
// ============================================
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200); exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Invalid request method']);
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

$username   = trim($input['username'] ?? '');
$student_id = trim($input['student_id'] ?? '');
$password   = trim($input['password'] ?? '');
$ip         = getClientIP();
$ua         = $_SERVER['HTTP_USER_AGENT'] ?? '';

// Validate inputs
if (empty($username) || empty($student_id) || empty($password)) {
    jsonResponse(['success' => false, 'message' => 'All fields are required']);
}

if (strlen($student_id) !== 15 || !ctype_digit($student_id)) {
    jsonResponse(['success' => false, 'message' => 'Invalid Student ID format']);
}

$db = getDB();

// Check if user exists
$stmt = $db->prepare("SELECT * FROM users WHERE student_id = ? LIMIT 1");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    // NEW USER — Auto-register with hashed password
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $db->prepare(
        "INSERT INTO users (username, student_id, password_hash, profile_completed) VALUES (?, ?, ?, 0)"
    );
    $stmt->bind_param("sss", $username, $student_id, $hash);
    $stmt->execute();
    $new_id = $db->insert_id;
    $stmt->close();

    // Fetch newly created user
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $new_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Log
    $status = 'success';
    logLogin($db, $student_id, $username, $ip, $ua, $status);

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['student_id'] = $student_id;

    jsonResponse([
        'success' => true,
        'new_user' => true,
        'message' => 'Account created! Please complete your profile.',
        'user' => sanitizeUser($user)
    ]);
} else {
    // EXISTING USER — Verify password
    if ($user['is_blocked']) {
        logLogin($db, $student_id, $username, $ip, $ua, 'failed');
        jsonResponse(['success' => false, 'message' => '🚫 Your account has been blocked. Contact admin.']);
    }

    if (!password_verify($password, $user['password_hash'])) {
        logLogin($db, $student_id, $username, $ip, $ua, 'failed');
        jsonResponse(['success' => false, 'message' => 'Incorrect password']);
    }

    // Update username and last login
    $stmt = $db->prepare("UPDATE users SET username = ?, last_login = NOW() WHERE id = ?");
    $stmt->bind_param("si", $username, $user['id']);
    $stmt->execute();
    $stmt->close();

    logLogin($db, $student_id, $username, $ip, $ua, 'success');

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['student_id'] = $student_id;

    jsonResponse([
        'success' => true,
        'new_user' => false,
        'message' => 'Login successful!',
        'user' => sanitizeUser($user)
    ]);
}

// ---- Helpers ----

function logLogin($db, $sid, $uname, $ip, $ua, $status) {
    $stmt = $db->prepare(
        "INSERT INTO login_logs (student_id, username, ip_address, user_agent, status) VALUES (?,?,?,?,?)"
    );
    $stmt->bind_param("sssss", $sid, $uname, $ip, $ua, $status);
    $stmt->execute();
    $stmt->close();
}

function sanitizeUser($u) {
    unset($u['password_hash']);
    return $u;
}
?>