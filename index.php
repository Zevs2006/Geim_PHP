<?php
session_start();

// Инициализация игры при первой загрузке страницы
if (!isset($_SESSION['game'])) {
    $_SESSION['game'] = [
        'target' => rand(1, 100), // Загаданное число
        'attempts' => [] // История попыток
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Угадай число</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #f4f4f4; }
        button { padding: 10px 15px; margin: 5px; cursor: pointer; }
        .hidden { display: none; }
    </style>
</head>
<body>
    <h1>Игра: Угадай число</h1>
    <p>Загадано число от 1 до 100. Попробуй угадать!</p>
    <button id="guess">Попробовать угадать</button>
    <button id="give-up">Сдаться</button>

    <!-- Таблица попыток -->
    <table>
        <thead>
            <tr>
                <th>Попытка</th>
                <th>Результат</th>
            </tr>
        </thead>
        <tbody id="attempts">
            <?php foreach ($_SESSION['game']['attempts'] as $attempt): ?>
                <tr>
                    <td><?= htmlspecialchars($attempt['guess']) ?></td>
                    <td><?= htmlspecialchars($attempt['result']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        document.getElementById('give-up').addEventListener('click', () => {
            if (confirm('Вы уверены, что хотите сдаться?')) {
                fetch('process.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'action=give_up'
                })
                .then(response => {
                    if (!response.ok) {
                        alert('Ошибка при обработке запроса. Попробуйте еще раз.');
                    }
                    return response.text();
                })
                .then(() => location.reload())
                .catch(error => {
                    console.error('Ошибка:', error);
                    alert('Не удалось выполнить действие. Проверьте подключение.');
                });
            }
        });


        document.getElementById('guess').addEventListener('click', () => {
            const number = prompt('Введите число от 1 до 100:');
            if (number !== null) {
                const guess = parseInt(number, 10);
                if (!isNaN(guess) && guess >= 1 && guess <= 100) {
                    fetch('process.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `action=guess&number=${encodeURIComponent(guess)}`
                    }).then(() => location.reload());
                } else {
                    alert('Пожалуйста, введите число от 1 до 100!');
                }
            }
        });

    </script>
</body>
</html>
