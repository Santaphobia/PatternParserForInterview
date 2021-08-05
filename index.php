<?php
require_once('parser.php');
require_once('database.php');

$db = getDataBase();

$str = $_POST['text'] ?? '';

echo <<<HTML
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Test</title>
</head>
<body>
<form action="/" method="post">
    <input type="text" name="text">
    <button>Parse</button>
</form>
</body>
</html>
HTML;

try {
    $answer = stringTegParser($str);
    $placeholders = '';
    foreach ($answer as $value) {
        $placeholders .= '(?),';
        echo  '<hr>'.$value ;
    }
    $placeholders = substr($placeholders, 0, -1);
    $query = $db->prepare("INSERT IGNORE INTO text (text) VALUES {$placeholders}");
    $query->execute($answer);
} catch (Exception $e) {
    echo $e->getMessage();
}









