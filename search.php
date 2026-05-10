<?php
session_start();
include 'db.php';

// Проверка прав
if (!isset($_SESSION['user_id']) || $_SESSION['can_search'] == 0) {
    die("У вас нет прав на использование поиска.");
}

$query_str = $_GET['q'] ?? '';
$stmt = $conn->prepare("SELECT * FROM employees WHERE last_name LIKE ?");
$param = "%$query_str%";
$stmt->bind_param("s", $param);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<div>" . $row['last_name'] . " " . $row['phone'] . "</div>";
}
?>