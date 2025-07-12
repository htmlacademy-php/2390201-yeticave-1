<?php
// Сценарий просмотра лота с конкретным id

// Подключение функций, инициализация переменных для работы сайта
require_once 'init.php';

/**
 * @var string user_name
 * @var int $is_auth
 * @var mysqli $connection
 */

//Чтение перечня категорий
require_once 'model/read_categories.php';

// Обработка входящего GET-параметра - id лота
if (!isset($_GET['id'])) {
  http_response_code(404);
  die("Ошибка вызова сценария показа лота - нет параметра -id лота-");
}
$lot_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

// Получение лота из БД по его id
require_once 'model/get_lot.php';
$lot = getLotById($connection, $lot_id);

// HTML - код для показа информации по лоту внутри layout
$page_content = include_template('lot.php', ['categories' => $categories, 'lot' => $lot]);

// окончательный HTML-код
$layout_content = include_template('layout.php', ['page_content' => $page_content, 'title' => $title, 'is_auth' => $is_auth, 'user_name' => $user_name, 'categories' => $categories]);

print($layout_content);

