<?php
function getDataBase(): PDO {
    $config = ['driver' => 'mysql', 'host' => 'localhost:8889', 'database' => 'parser_text', 'charset' => 'utf8', 'login' => 'root', 'password' => 'root'];
    $db = new PDO(sprintf("%s:host=%s;dbname=%s;charset=%s",
        $config['driver'],
        $config['host'],
        $config['database'],
        $config['charset']
    ), $config['login'], $config['password']);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
}

