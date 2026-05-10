<?php 
include 'config.php';
checkRole('admin'); 

// Логика изменения доступа
if(isset($_POST['toggle_search'])) {
    $uid = intval($_POST['user_id']);
    $new_status = $_POST['current_status'] ? 0 : 1;
    $conn->query("UPDATE users SET can_search = $new_status WHERE id = $uid");
    
    $action = $new_status ? 'Разблокировка поиска' : 'Блокировка поиска';
    $stmt = $conn->prepare("INSERT INTO block_history (user_id, action) VALUES (?, ?)");
    $stmt->bind_param("is", $uid, $action);
    $stmt->execute();
    
    header("Location: admin.php"); // Перезагрузка для обновления данных
    exit;
}

// Получение статистики
$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$blocked_users = $conn->query("SELECT COUNT(*) as count FROM users WHERE can_search = 0")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Панель администратора</title>
</head>
<body class="admin-body">

<header>
    <h1>Админ-панель</h1>
    <div class="auth-box">
        <a href="index.php">На сайт</a>
        <a href="logout.php" style="color: var(--danger);">Выйти</a>
    </div>
</header>

<main class="admin-container">
    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-label">Всего сотрудников</span>
            <span class="stat-value"><?= $total_users ?></span>
        </div>
        <div class="stat-card">
            <span class="stat-label">Без доступа к поиску</span>
            <span class="stat-value" style="color: var(--danger);"><?= $blocked_users ?></span>
        </div>
        <div class="stat-card">
            <span class="stat-label">Активные роли</span>
            <span class="stat-value" style="color: var(--success);">Admin, Employee</span>
        </div>
    </div>

    <div class="admin-content">
        <section class="admin-section">
            <h2><i class="icon">👤</i> Управление доступом</h2>
            <div class="table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Пользователь</th>
                            <th>Роль</th>
                            <th>Доступ к поиску</th>
                            <th>Действие</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $users = $conn->query("SELECT * FROM users ORDER BY id DESC");
                        while($u = $users->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= $u['id'] ?></td>
                            <td><strong><?= htmlspecialchars($u['username']) ?></strong></td>
                            <td><span class="role-badge <?= $u['role'] ?>"><?= $u['role'] ?></span></td>
                            <td>
                                <span class="status-indicator <?= $u['can_search'] ? 'active' : 'inactive' ?>">
                                    <?= $u['can_search'] ? 'Разрешен' : 'Запрещен' ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" style="margin:0;">
                                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                    <input type="hidden" name="current_status" value="<?= $u['can_search'] ?>">
                                    <button name="toggle_search" class="btn-action <?= $u['can_search'] ? 'btn-block' : 'btn-unblock' ?>">
                                        <?= $u['can_search'] ? 'Заблокировать' : 'Разрешить' ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="admin-section">
            <h2><i class="icon">📜</i> История последних блокировок</h2>
            <div class="log-list">
                <?php
                $logs = $conn->query("SELECT h.*, u.username FROM block_history h JOIN users u ON h.user_id = u.id ORDER BY h.date_time DESC LIMIT 10");
                if($logs->num_rows > 0):
                    while($l = $logs->fetch_assoc()): ?>
                    <div class="log-item">
                        <span class="log-date"><?= date('d.m.Y H:i', strtotime($l['date_time'])) ?></span>
                        <span class="log-user"><?= htmlspecialchars($l['username']) ?></span>
                        <span class="log-action <?= strpos($l['action'], 'Разб') !== false ? 'txt-success' : 'txt-danger' ?>">
                            <?= $l['action'] ?>
                        </span>
                    </div>
                <?php endwhile; 
                else: echo "<p>История пуста</p>"; endif; ?>
            </div>
        </section>
    </div>
</main>

</body>
</html>