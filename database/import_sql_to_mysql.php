<?php
// Import only INSERT statements from sql_dump.sql into MySQL
$host = '127.0.0.1';
$db   = 'fisiocare';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';
$input = __DIR__ . DIRECTORY_SEPARATOR . 'sql_dump.sql';

if (!file_exists($input)) {
    fwrite(STDERR, "SQL dump not found: $input\n");
    exit(1);
}

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (Exception $e) {
    fwrite(STDERR, "DB connection failed: " . $e->getMessage() . "\n");
    exit(1);
}

$contents = file_get_contents($input);
$lines = preg_split('/;\s*\n/', $contents); // split by semicolon+newline
$insertCount = 0;
$errors = 0;

foreach ($lines as $stmt) {
    $stmt = trim($stmt);
    if ($stmt === '') continue;
    // Only process INSERT statements
    if (stripos($stmt, 'INSERT INTO') === 0) {
        try {
            $pdo->exec($stmt . ';');
            $insertCount++;
        } catch (Exception $e) {
            // Log and continue
            fwrite(STDERR, "Failed to execute INSERT: " . substr($stmt,0,200) . "...\nError: " . $e->getMessage() . "\n");
            $errors++;
        }
    }
}

fwrite(STDOUT, "Inserted: $insertCount statements. Errors: $errors\n");
exit($errors > 0 ? 1 : 0);
