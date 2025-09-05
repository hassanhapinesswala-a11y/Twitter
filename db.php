<?php
$host = 'localhost';
$db = 'dbjjhsafhgtyy9';
$user = 'uyhezup6l0hgf';
$pass = 'pr634bpk3knb';
try {
$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
} catch (PDOException $e) { die('DB Connection failed: '.htmlspecialchars($e->getMessage())); }
if (session_status()===PHP_SESSION_NONE) session_start();
function auth_required(){ if(!isset($_SESSION['user_id'])){ echo '<script>location.href="login.php"</script>'; exit; }}
?>
