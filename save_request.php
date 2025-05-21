<?php
header('Content-Type: application/json');

// Параметры подключения к базе данных
$host = 'localhost';
$dbname = 'project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;port=3307;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // $pdo->exec("SET NAMES utf8mb4");

    // Получение данных из формы
    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : null;
    $course = isset($_POST['course']) ? trim($_POST['course']) : null;
    $message = isset($_POST['message']) ? trim($_POST['message']) : null;

    // Проверка на наличие обязательных полей
    if (!$name || !$email || !$phone || !$course) {
        throw new PDOException('Missing required fields');
    }

    // Валидация email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new PDOException('Invalid email format');
    }

    // Подготовленный запрос для предотвращения SQL-инъекций
    $stmt = $pdo->prepare("INSERT INTO requests (name, email, phone, course, message, created_at) VALUES (:name, :email, :phone, :course, :message, NOW())");
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':phone' => $phone,
        ':course' => $course,
        ':message' => $message
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>