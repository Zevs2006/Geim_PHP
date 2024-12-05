<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;

    if ($action === 'guess') {
        $number = (int)($_POST['number'] ?? 0);

        if ($number < 1 || $number > 100) {
            $_SESSION['game']['attempts'][] = [
                'guess' => $number,
                'result' => 'Ошибка: число должно быть от 1 до 100!'
            ];
            header('Location: index.php');
            exit;
        }

        if ($number < $_SESSION['game']['target']) {
            $result = 'Больше';
        } elseif ($number > $_SESSION['game']['target']) {
            $result = 'Меньше';
        } else {
            $result = 'Поздравляем! Вы угадали!';
            $_SESSION['game'] = [
                'target' => rand(1, 100),
                'attempts' => []
            ];
        }

        $_SESSION['game']['attempts'][] = [
            'guess' => $number,
            'result' => $result
        ];
    } elseif ($action === 'give_up') {
        // Сброс состояния игры
        $_SESSION['game'] = [
            'target' => rand(1, 100),
            'attempts' => []
        ];
    }
}

header('Location: index.php');
exit;
