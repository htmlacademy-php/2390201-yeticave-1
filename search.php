<?php
// Содержит сценарий поиска лота
// Подключение функций, инициализация переменных для работы сайта
require_once 'init.php';

/**
 * @var mysqli $connection
 */

//Чтение перечня категорий
require_once 'model/read_categories.php';

// Подключаем функции pagesNumber и lotsFinded
require_once 'model/search_lots.php';

// Определяем начальные параметры пагинации
$current_page = isset($_GET['page'])
  ? filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT)
  : 1;
$offset = ($current_page - 1) * LOTS_ON_PAGE;
$pages_number = 0;

// Определяем содержимое поискового запроса
$search_content = isset($_GET['search'])
  ? strval(htmlspecialchars($_GET['search']))
  : '';

// Получение списка лота по поисковому запросу и определение кол-ва страниц пагинации
if ($search_content) {
  $lots_finded = lotsFinded($connection, $search_content, $offset);
  $pages_number = pagesNumber($connection, $search_content);
}

$page_content = include_template('search-page.php', ['search_content' => $search_content, 'lots_finded' => $lots_finded, 'pages_number' => $pages_number, 'current_page' => $current_page,'offset' => $offset]);

$layout_content = include_template('layout.php', ['page_content' => $page_content, 'title' => $title, 'categories' => $categories]);

print($layout_content);

