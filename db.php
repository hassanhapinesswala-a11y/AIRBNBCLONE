<?php
// db.php
$DB_HOST = 'localhost';
$DB_NAME = 'db1ojne0veweob';
$DB_USER = 'uyhezup6l0hgf';
$DB_PASS = 'pr634bpk3knb';
 
try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Exception $e) {
    echo "Database connection failed: " . htmlspecialchars($e->getMessage());
    exit;
}
?>
