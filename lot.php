<?php
// Сценарий просмотра лота с конкретным id

// Подключение функций, инициализация переменных для работы сайта
require_once 'init.php';

//Чтение перечня категорий
require_once 'model/read_categories.php';

// Обработка входящего GET-параметра - id лота
if (!isset($_GET['id'])) {
  http_response_code(404);
  die("Ошибка вызова сценария показа лота - нет параметра -id лота-");
}
$lot_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

// Получение лота из БД по его id
require_once 'model/treat_lot.php';
$lot = getLotById($connection, $lot_id);

// обрабатываем форму ввода ставки по лоту
$errors = [];
if($_SERVER['REQUEST_METHOD']==='POST') {
  $bet_and_errors = getBetAndErrors($lot);
  $bet = $bet_and_errors[0];
  $errors = $bet_and_errors[1];

  if(!count($errors)) {
    addBet($connection, $bet);
  }
}

// Получаем все ставки по лоту, в т.ч. возможно добавленную из формы
$lot_bets = getLotBets($connection, $lot_id);

// Устанавливаем флаг показа блока добавления ставки на странице
$add_lot_allowed = addLotAllowed ($lot, $lot_bets);

// HTML - код для показа информации по лоту внутри layout
$page_content = include_template('lot.php', ['categories' => $categories, 'lot' => $lot, 'lot_bets' => $lot_bets, 'add_lot_allowed' => $add_lot_allowed,'errors' => $errors]);

// окончательный HTML-код
$layout_content = include_template('layout.php', ['page_content' => $page_content, 'title' => $title, 'categories' => $categories, 'selected_category' => 0]);

print($layout_content);

