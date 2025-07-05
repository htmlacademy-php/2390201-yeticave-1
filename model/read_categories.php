<?php
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
