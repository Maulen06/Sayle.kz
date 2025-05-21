<?php
header('Content-Type: application/json');

// Параметры подключения к базе данных
$host = 'localhost';
$dbname = 'project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;port=3307;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Получение данных из формы
    $courseId = $_POST['course_id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];

    // Подготовленный запрос для предотвращения SQL-инъекций
    $stmt = $pdo->prepare("INSERT INTO course_selections (course_id, name, phone, created_at) VALUES (:course_id, :name, :phone, NOW())");
    $stmt->execute([
        ':course_id' => $courseId,
        ':name' => $name,
        ':phone' => $phone
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
