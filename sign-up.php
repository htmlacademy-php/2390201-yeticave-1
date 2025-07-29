<?php
// Сценарий регистрации нового пользователя на сайте

// Подключение функций, инициализация переменных для работы сайта
require_once 'init.php';

// Страница закрыта для зарегистрированных пользователей
if (isset($_SESSION['user'])) {
  http_response_code(403);
  die("Страница регистрации пользователя закрыта для уже вошедших на сайт пользователей. Пожалуйста, выполните 'Выход'.");
}

//Чтение перечня категорий
require_once 'model/read_categories.php';

// Подключаем функцию getUserAndErrors заполнения полей нового пользователя из массива $_POST
// и функцию addUser добавления нового пользователя в БД
require_once 'model/add_user.php';

// Отрабатываем нажатие кнопки добавления нового пользователя
$errors = [];
if($_SERVER['REQUEST_METHOD']==='POST') {
  $user_and_errors = getUserAndErrors($connection);
  $user = $user_and_errors[0];
  $errors = $user_and_errors[1];

  if(!count($errors)) {
    addUser($connection, $user);
  }
}

// HTML - код для отображения формы регистрации нового пользователя
$page_content = include_template('sign-up.php', ['categories' => $categories, 'errors' => $errors]);

$layout_content = include_template('layout.php', ['page_content' => $page_content, 'title' => $title, 'categories' => $categories, 'selected_category' => 0]);

print($layout_content);
