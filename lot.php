<?php
// Подключение функций, инициализация переменных для работы сайта
require_once 'init.php';

//Чтение перечня категорий
include_once 'uploads/read_categories.php';

// Фильтрация $user_name для защиты от XSS - удаляем все html-теги.
$user_name = strip_tags($user_name);

// Обработка входящего GET-параметра - id лота
if (!isset($_GET['id'])) {
  http_response_code(404);
  die("Ошибка вызова сценария показа лота - нет параметра -id лота-");
}
$lot_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

//Получаем лот по его id
$sql_take_lot = "SELECT
    lots.id,
    lots.name,
    lots.description,
    lots.image,
    lots.start_price,
    lots.expire_date,
    lots.bet_step,
    lots.author_id,
    lots.winner_id,
    lots.category_id,
    categories.name AS category
FROM lots
JOIN categories ON lots.category_id = categories.id
WHERE lots.id = ?;";

$stmt_lot = mysqli_prepare($connection, $sql_take_lot);
mysqli_stmt_bind_param($stmt_lot, 'i', $lot_id);
mysqli_stmt_execute($stmt_lot);
$result_lot = mysqli_stmt_get_result($stmt_lot);
if (!$result_lot) {
  http_response_code(404);
	die("Ошибка чтения лота с id=" . strval($lot_id));
};
$lot = mysqli_fetch_assoc($result_lot);

// Фильтрация $lot для защиты от XSS - удаляем все html-теги.
foreach($lot as $key => $value) {
  $lot[$key] = strip_tags($value);
};

// Находим максимальную ставку для лота с id = $lot_id, помещаем в $lot_price_array['price']
$sql_take_lot_price = "SELECT MAX(bets.price) AS price FROM bets WHERE bets.lot_id = ?;";
$stmt_lot_price = mysqli_prepare($connection, $sql_take_lot_price);
mysqli_stmt_bind_param($stmt_lot_price, 'i', $lot_id);
mysqli_stmt_execute($stmt_lot_price);
$result_lot_price = mysqli_stmt_get_result($stmt_lot_price);
if (!$result_lot_price) {
	die("Ошибка чтения макс.цены лота");
};
$lot_price_array = mysqli_fetch_assoc($result_lot_price);

// Текущая цена - либо максимальная ставка, полученная предыдущим запросом (если она не NULL), либо стартовая цена лота
$lot_price = $lot_price_array['price'] ?? $lot['start_price'];
$lot_bet = $lot_price + $lot['bet_step']; // Минимальная ставка по лоту

// HTML - код для показа информации по лоту внутри layout
$page_content = include_template('lot.php', ['categories' => $categories, 'lot' => $lot, 'timer_finishing_hours' => $timer_finishing_hours, 'lot_price' => $lot_price, 'lot_bet' => $lot_bet]);

// окончательный HTML-код
$layout_content = include_template('layout.php', ['page_content' => $page_content, 'title' => $title, 'is_auth' => $is_auth, 'user_name' => $user_name, 'categories' => $categories]);

print($layout_content);
?>
