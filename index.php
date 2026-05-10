<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Телефонный справочник</title>
</head>
<body>
    <header>
        <h1>Справочник организации</h1>
        <div class="auth-box">
            <?php if(isset($_SESSION['user_id'])): ?>
                Привет, <?= $_SESSION['username'] ?>! | <a href="logout.php">Выйти</a>
                <?php if($_SESSION['role'] == 'admin') echo '| <a href="admin.php">Админка</a>'; ?>
            <?php else: ?>
                <a href="login.php">Вход для сотрудников</a>
            <?php endif; ?>
        </div>
    </header>

    <main>
        <h2>Наши отделы</h2>
        <div class="dept-grid">
            <?php
            $res = $conn->query("SELECT * FROM departments");
            while($dept = $res->fetch_assoc()): ?>
                <div class="dept-card">
                    <h3><?= $dept['name'] ?></h3>
                    <p><b>Руководитель:</b> <?= $dept['manager_name'] ?></p>
                    <p><?= $dept['description'] ?></p>
                </div>
            <?php endwhile; ?>
        </div>

        <?php if(isset($_SESSION['can_search']) && $_SESSION['can_search']): ?>
            <hr>
            <h2>Поиск сотрудников</h2>
            <input type="text" id="searchInput" placeholder="Введите фамилию или отдел...">
            <div id="results"></div>
        <?php elseif(isset($_SESSION['user_id'])): ?>
            <p class="warning">Доступ к поиску ограничен администратором.</p>
        <?php endif; ?>
    </main>

    <script>
    document.getElementById('searchInput')?.addEventListener('input', function() {
        let val = this.value;
        if(val.length < 2) { document.getElementById('results').innerHTML = ''; return; }
        
        fetch('search_handler.php?q=' + encodeURIComponent(val))
            .then(res => res.text())
            .then(data => document.getElementById('results').innerHTML = data);
    });
    </script>
</body>
</html>