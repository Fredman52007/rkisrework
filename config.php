<?php
session_start();
$conn = new mysqli("localhost", "root", "", "phonebook_db");
if ($conn->connect_error) die("Ошибка: " . $conn->connect_error);
$conn->set_charset("utf8");

// Вспомогательная функция для проверки ролей
function checkRole($role) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        header("Location: login.php");
        exit;
    }
}
?>