<?php
// Сценарий просмотра всех ставок, сделанных текущим авторизованным пользователем

// Подключение функций, инициализация переменных для работы сайта
require_once 'init.php';

// Страница закрыта для анонимных пользователей
if (!isset($_SESSION['user'])) {
  http_response_code(403);
  die("Страница просмотра сделанных ставок закрыта для анонимных пользователей.");
}

//Чтение перечня категорий
require_once 'model/read_categories.php';

// Получение списка ставок по пользователю
require_once 'model/get_bets.php';
$my_bets = getLotBets($connection, $_SESSION['user']['id']);

$page_content = include_template('my-bets-page.php', ['categories' => $categories, 'my_bets' => $my_bets]);

$layout_content = include_template('layout.php', ['page_content' => $page_content, 'title' => $title, 'is_auth' => $is_auth, 'user_name' => $user_name, 'categories' => $categories]);

print($layout_content);
