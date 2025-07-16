<?php
// db.php
$host = 'bbfqlskx1r4gteljmebk-mysql.services.clever-cloud.com';
$db = 'bbfqlskx1r4gteljmebk';
$user = 'u3rajzmt4gple1rj';
$pass = 'pGJEz1VAeaSvdcH2Fj2F';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}
?>