<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

// Ambil data JSON dari request body
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

if (!$data || empty($data['email']) || empty($data['password'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Email dan password wajib diisi']);
    exit;
}

$email = $data['email'];
$password = $data['password'];

// Koneksi ke database
$host = 'localhost';
$db   = 'drinkstory';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Ambil user berdasarkan email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Berhasil login
        echo json_encode([
            'message' => 'Login berhasil',
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'] ?? 'customer' // fallback ke customer jika kosong
        ]);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Email atau password salah']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
