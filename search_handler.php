<?php
include 'config.php';

if (!isset($_SESSION['can_search']) || $_SESSION['can_search'] == 0) {
    die("<p class='danger'>Доступ ограничен</p>");
}

$q = $_GET['q'] ?? '';
$q = "%$q%";

$stmt = $conn->prepare("SELECT e.*, d.name as dept_name 
                        FROM employees e 
                        JOIN departments d ON e.dept_id = d.id 
                        WHERE e.last_name LIKE ? OR d.name LIKE ?");
$stmt->bind_param("ss", $q, $q);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<table class='results-table'>
            <tr>
                <th>ФИО</th>
                <th>Отдел</th>
                <th>Должность</th>
                <th>Кабинет</th>
                <th>Телефон</th>
            </tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['last_name']} {$row['first_name']}</td>
                <td>{$row['dept_name']}</td>
                <td>{$row['position']}</td>
                <td>{$row['room_number']}</td>
                <td><b>{$row['phone']}</b></td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p style='padding:20px;'>Ничего не найдено</p>";
}
?>