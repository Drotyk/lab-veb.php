<?php
// Київський час
date_default_timezone_set('Europe/Kyiv');

$submitted = false;
$submittedAt = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Читаємо дані з форми
    $name   = trim($_POST['name']   ?? '');
    $email  = trim($_POST['email']  ?? '');
    $q1     = trim($_POST['q1']     ?? '');
    $q2     = trim($_POST['q2']     ?? '');
    $q3     = trim($_POST['q3']     ?? '');

    // Мінімальна валідація
    if ($name === '' || $email === '') {
        $error = 'Будь ласка, заповніть ім’я та email.';
    } else {
        // 2. Масив з відповідями
        $data = [
            'name'  => $name,
            'email' => $email,
            'answers' => [
                'q1' => $q1,
                'q2' => $q2,
                'q3' => $q3
            ],
            'submitted_at' => date('Y-m-d H:i:s')
        ];

        // 3. Папка для збереження файлів
        $dir = __DIR__ . '/survey';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        // 4. Ім’я файлу з датою і часом
        $filename = $dir . '/survey_' . date('Y-m-d_H-i-s') . '.json';

        // 5. Збереження у JSON
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        if (file_put_contents($filename, $json) === false) {
            $error = 'Помилка збереження файлу з відповіддю.';
        } else {
            $submitted = true;
            $submittedAt = $data['submitted_at'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Анкета опитування</title>
    <style>
        body {
            font-family: system-ui, sans-serif;
            background: #111;
            color: #f5f5f5;
            display: flex;
            justify-content: center;
            padding: 2rem;
        }
        .container {
            background: #1c1c1c;
            padding: 2rem;
            border-radius: 1rem;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
        }
        label {
            display: block;
            margin-top: 1rem;
            margin-bottom: 0.25rem;
        }
        input[type="text"],
        input[type="email"],
        textarea,
        select {
            width: 100%;
            padding: 0.5rem;
            border-radius: 0.5rem;
            border: 1px solid #444;
            background: #222;
            color: #f5f5f5;
        }
        button {
            margin-top: 1.5rem;
            padding: 0.6rem 1.5rem;
            border: none;
            border-radius: 999px;
            cursor: pointer;
            background: #3b82f6;
            color: #fff;
            font-weight: 600;
        }
        .message {
            margin-top: 1rem;
            padding: 0.8rem 1rem;
            border-radius: 0.5rem;
        }
        .message.success {
            background: #14532d;
            color: #bbf7d0;
        }
        .message.error {
            background: #450a0a;
            color: #fecaca;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Анкета опитування</h1>
    <p>Тема: Ваше ставлення до штучного інтелекту</p>

    <?php if ($submitted && !$error): ?>
        <div class="message success">
            <strong>Дякуємо за вашу відповідь, <?= htmlspecialchars($name) ?>!</strong><br>
            Форма була заповнена: <strong><?= htmlspecialchars($submittedAt) ?></strong>.
        </div>
    <?php elseif ($error): ?>
        <div class="message error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="post" action="survey.php">
        <label for="name">Ім’я</label>
        <input type="text" id="name" name="name"
               value="<?= isset($name) ? htmlspecialchars($name) : '' ?>" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email"
               value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>

        <label for="q1">1. Як ви оцінюєте свій рівень знань про ШІ?</label>
        <select id="q1" name="q1">
            <option value="Початковий">Початковий</option>
            <option value="Середній">Середній</option>
            <option value="Високий">Високий</option>
        </select>

        <label for="q2">2. Де, на вашу думку, ШІ найбільше корисний?</label>
        <input type="text" id="q2" name="q2" placeholder="Наприклад: медицина, освіта, ігри тощо">

        <label for="q3">3. Які ризики використання ШІ ви бачите?</label>
        <textarea id="q3" name="q3" rows="4"></textarea>

        <button type="submit">Надіслати</button>
    </form>
</div>
</body>
</html>
