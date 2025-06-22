<?php
// Подключаем дополнительные функции, специально написанные для курса
require_once('helpers.php');

// Данные, которыми наполняем сайт
$title = 'YetiCave - Главная';
$is_auth = rand(0, 1);
$user_name = 'Роман Носков'; // укажите здесь ваше имя
$timer_finishing_hours = 0; // Количество часов, при котором нужно включать модификатор истечения времени лота

$categories = [
  [
    'id' => 0,
    'name' => '',
    'code' => ''
  ]
];

$lots = [
  [
    'id' => 0,
    'name' => '',
    'description' => '',
    'image' => '',
    'start_price' => 0,
    'expire_date' => '',
    'bet_step' => 0,
    'author_id' => 0,
    'winner_id' => 0,
    'category_id' => 0,
    'category' => ''
  ]
];

// Устанавливаем текущую временную зону "Москва GMT+3" для правильной работы с датами
date_default_timezone_set('Europe/Moscow');

// Функция get_dt_range принимает в качестве параметра дату в формате ГГГГ-ММ-ДД,
// и возвращает массив строк, где первый элемент — целое количество часов до даты,
// а второй — остаток в минутах.
function get_dt_range(string $expire_date) {
  $time_left = ['00', '00'];

  $current_time = time();
  $expire_time = strtotime($expire_date);

  $minutes_difference = floor(($expire_time - $current_time) / 60);
  if ($minutes_difference > 0) {
    $time_left[0] = str_pad(strval(floor($minutes_difference / 60)), 2, '0', STR_PAD_LEFT);
    $time_left[1] = str_pad(strval($minutes_difference % 60), 2, '0', STR_PAD_LEFT);
  }
  return $time_left;
}

//Установление SQL-соединения с базой данных yeticave
$connection = mysqli_connect("localhost", "sqladmin", "1234","yeticave");
mysqli_set_charset($connection, "utf8mb4");

//Чтение перечня категорий
$sql_take_categories = "SELECT * FROM categories";
$result_categories = mysqli_query($connection, $sql_take_categories);
if (!$result_categories) {
	die("Ошибка чтения перечня категорий (TABLE categories)");
};
$categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);

// Фильтрация $categories для защиты от XSS - удаляем все html-теги.
foreach($categories as $key => $value) {
  foreach($categories[$key] as $category_key => $category_value) {
    $categories[$key][$category_key] = strip_tags($category_value);
  }
}

//Чтение перечня лотов
$sql_take_lots = "SELECT
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
FROM lots JOIN categories ON lots.category_id = categories.id GROUP BY lots.id;";

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

// Фильтрация $user_name для защиты от XSS - удаляем все html-теги.
$user_name = strip_tags($user_name);

// HTML-код тега <main> главной страницы
$page_content = include_template('main.php', ['categories' => $categories, 'lots' => $lots, 'timer_finishing_hours' => $timer_finishing_hours]);

// окончательный HTML-код
$layout_content = include_template('layout.php', ['page_content' => $page_content, 'title' => $title, 'is_auth' => $is_auth, 'user_name' => $user_name, 'categories' => $categories]);

print($layout_content);
?>
