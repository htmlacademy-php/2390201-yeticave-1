<?php
// Сценарий входа пользователя на сайт

// Подключение функций, инициализация переменных для работы сайта
require_once 'init.php';

//Чтение перечня категорий
require_once 'model/read_categories.php';

// Подключаем функции getCredentialsAndErrors и userLogin
require_once 'model/user_login.php';

// Отрабатываем нажатие кнопки входа пользователя на сайт
$errors = [];
if($_SERVER['REQUEST_METHOD']==='POST') {
  $credentials_and_errors = getCredentialsAndErrors($connection);
  $credentials = $credentials_and_errors[0];
  $errors = $credentials_and_errors[1];

  if(!count($errors)) {
    userLogin($connection, $credentials, $errors); // из ошибок может выпасть "не найден пользователь" и "пароль неверен" (не совпадает с хранимым в БД)
  }
}

// HTML - код для отображения формы входа пользователя на сайт
$page_content = include_template('login-page.php', ['categories' => $categories, 'errors' => $errors]);

$layout_content = include_template('layout.php', ['page_content' => $page_content, 'title' => $title, 'categories' => $categories, 'selected_category' => 0]);

print($layout_content);
