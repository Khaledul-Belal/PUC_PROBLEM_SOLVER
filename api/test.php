<?php
require_once 'config.php';
$db = getDB();
$result = $db->query("SELECT COUNT(*) as total FROM users");
$row = $result->fetch_assoc();
echo "Connected! Users: " . $row['total'];
?>