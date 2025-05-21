<?php
header('Content-Type: application/json');
$host = 'localhost';
$dbname = 'project';
$username = 'root';
$password = '';
try {
    $pdo = new PDO("mysql:host=$host;port=3307;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // $pdo->exec("SET NAMES utf8mb4");

    if (empty($_POST)) {
        throw new Exception('No POST data received');
    }

    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $message = isset($_POST['message']) ? trim($_POST['message']) : null;

    if (!$name || !$email || !$message) {
        throw new Exception('Missing required fields');
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }

    $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message, created_at) VALUES (:name, :email, :message, NOW())");
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':message' => $message
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (Exception $e) {
    error_log('General error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>