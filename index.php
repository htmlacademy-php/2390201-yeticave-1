<?php
// Основной сценарий работы сайта

// Подключение функций, инициализация переменных для работы сайта
require_once 'init.php';

// Определение списка победителей
require_once 'getwinner.php';

//Чтение перечня категорий
include_once 'model/read_categories.php';

//Чтение перечня лотов
$sql_take_lots = "SELECT
    lots.id,
    lots.name,
    lots.description,
    lots.image,
    lots.start_price,
    lots.publish_date,
    lots.expire_date,
    lots.bet_step,
    lots.author_id,
    lots.winner_id,
    lots.category_id,
    categories.name AS category
FROM lots
JOIN categories ON lots.category_id = categories.id
GROUP BY lots.id
ORDER BY lots.publish_date DESC;";

$result_lots = mysqli_query($connection, $sql_take_lots);
if (!$result_lots) {
	die("Ошибка чтения перечня лотов (TABLE lots)");
};
$lots = mysqli_fetch_all($result_lots, MYSQLI_ASSOC);

// Фильтрация $lots для защиты от XSS - удаляем все html-теги.
foreach($lots as $key => $value) {
  foreach($lots[$key] as $lot_key => $lot_value) {
    $lots[$key][$lot_key] = strip_tags($lot_value);
  }
}

// HTML-код тега <main> главной страницы
$page_content = include_template('main.php', ['categories' => $categories, 'lots' => $lots]);

// окончательный HTML-код
$layout_content = include_template('layout-main.php', ['page_content' => $page_content, 'title' => $title, 'categories' => $categories, 'selected_category' => 0]);

print($layout_content);

