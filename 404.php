<?php
// Сценарий отображения сообщения 404 Страница не найдена

// Подключение функций, инициализация переменных для работы сайта
require_once 'init.php';

//Чтение перечня категорий
require_once 'model/read_categories.php';

$page_content = include_template('404-page.php');

$layout_content = include_template('layout.php', ['page_content' => $page_content, 'title' => $title, 'categories' => $categories, 'selected_category' => 0]);

print($layout_content);

