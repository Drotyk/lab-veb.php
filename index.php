<?php
// Визначаємо змінні заздалегідь, щоб уникнути помилок
$message = "";

// Обробка форми
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Отримання даних
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $q1 = htmlspecialchars($_POST['q1']);
    $q2 = htmlspecialchars($_POST['q2']);
    $q3 = htmlspecialchars($_POST['q3']);
    
    $currentDateTime = date("Y-m-d H:i:s");

    // Формування вмісту файлу
    $data = "Час: $currentDateTime\n";
    $data .= "Ім'я: $name\nEmail: $email\n";
    $data .= "Q1 (Distro): $q1\nQ2 (Editor): $q2\nQ3 (Comment): $q3\n";
    $data .= "-------------------------\n";

    // Генерація імені файлу
    $filename = "survey/" . date("Y-m-d_H-i-s") . "_response.txt";

    // Спроба запису
    if (file_put_contents($filename, $data)) {
        $message = "<div class='success'>
                        ✅ <b>Дані збережено!</b><br>
                        Файл створено: <code>$filename</code><br>
                        Час: $currentDateTime
                    </div>";
    } else {
        $message = "<div style='color:red; font-weight:bold; padding: 15px; border: 1px solid red;'>
                        ❌ Помилка запису! Перевірте права доступу до папки survey (chmod).
                    </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Лабораторна №6 (Linux Mint)</title>
    <style>
        body { font-family: 'Ubuntu', sans-serif; max-width: 600px; margin: 20px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"], textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        button { background-color: #87cf3e; border: none; padding: 10px 20px; cursor: pointer; font-weight: bold; color: #fff; }
        button:hover { background-color: #6dbf2e; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; border: 1px solid #c3e6cb; margin-bottom: 20px; }
    </style>
</head>
<body>

<h2>Анкета опитування (Linux Environment)</h2>

<?php echo $message; ?>

<form action="index.php" method="POST">
    <div class="form-group">
        <label>Ім'я:</label>
        <input type="text" name="name" required>
    </div>

    <div class="form-group">
        <label>Email:</label>
        <input type="email" name="email" required>
    </div>

    <div class="form-group">
        <label>1. Який Linux дистрибутив вам подобається?</label>
        <label><input type="radio" name="q1" value="Mint" checked> Linux Mint</label>
        <label><input type="radio" name="q1" value="Ubuntu"> Ubuntu</label>
        <label><input type="radio" name="q1" value="Arch"> Arch (I use Arch btw)</label>
    </div>

    <div class="form-group">
        <label>2. Улюблений редактор коду:</label>
        <select name="q2">
            <option value="VS Code">VS Code</option>
            <option value="Nano">Nano</option>
            <option value="Vim">Vim</option>
            <option value="PHPStorm">PHPStorm</option>
        </select>
    </div>

    <div class="form-group">
        <label>3. Коментар:</label>
        <textarea name="q3" rows="3"></textarea>
    </div>

    <button type="submit">Надіслати</button>
</form>

</body>
</html>