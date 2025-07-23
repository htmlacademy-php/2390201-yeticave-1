<?php
// Содержит сценарий поиска лота
// Подключение функций, инициализация переменных для работы сайта
require_once 'init.php';

/**
 * @var mysqli $connection
 */

//Чтение перечня категорий
require_once 'model/read_categories.php';

// Подключаем функции categoryNameByID, categoryPagesNumber и categoryLotsFinded
require_once 'model/lots_by_category.php';

// Определяем начальные параметры пагинации
$current_page = isset($_GET['page'])
  ? filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT)
  : 1;
$offset = ($current_page - 1) * LOTS_ON_PAGE;
$pages_number = 0;

// Определяем категорию, по которой выбираем лоты
$category_id = isset($_GET['category'])
  ? filter_input(INPUT_GET, 'category', FILTER_SANITIZE_NUMBER_INT)
  : 0;

// Получение списка лотов по категории и определение кол-ва страниц пагинации
$lots_finded = [];
$category_name = categoryNameByID($categories, $category_id);
if ($category_id != 0) {
  $lots_finded = categoryLotsFinded($connection, $category_id, $offset);
  $pages_number = categoryPagesNumber($connection, $category_id);
}

$page_content = include_template('all-lots-page.php', ['category_name' => $category_name, 'category_id' => $category_id, 'lots_finded' => $lots_finded, 'pages_number' => $pages_number, 'current_page' => $current_page,'offset' => $offset]);

$layout_content = include_template('layout.php', ['page_content' => $page_content, 'title' => $title, 'categories' => $categories, 'selected_category' => $category_id]);

print($layout_content);

