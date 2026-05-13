<?php
$host = '127.0.0.1';
$db   = 'fisiocare';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $tables = ['usuarios','users','pacientes','fisioterapeutas','citas','historiales_clinicos','especialidades','migrations','sessions','cache'];
    foreach ($tables as $t) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) AS c FROM `$t`");
            $c = $stmt->fetch(PDO::FETCH_ASSOC)['c'];
            echo "$t: $c\n";
        } catch (Exception $e) {
            echo "$t: ERROR - " . $e->getMessage() . "\n";
        }
    }
} catch (Exception $e) {
    echo "Connection error: " . $e->getMessage() . "\n";
    exit(1);
}
