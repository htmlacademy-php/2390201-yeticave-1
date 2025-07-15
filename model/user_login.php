<?php

// Заполняет логин (e-mail) и пароль пользователя из массива $_POST, одновременно валидируя поля на ошибки
function getCredentialsAndErrors(mysqli $connection): array {
  $credentials = [];
  $errors = [];

  $credentials['email'] = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
  if (!$credentials['email']) {
    $errors['email'] = "Введите e-mail в формате user@example.com";
  } else {
    $errors['email'] = validateLength($credentials['email'], 1, 128);
  }

  $errors['password'] = validateLength($_POST['password'], 1, 128);
  if (!$errors['password']) {
    $credentials['password'] = htmlspecialchars($_POST['password']);
  } else {
    $credentials['password'] = null;
  }

  // Оставляем в массиве ошибок только не null значения
  $errors = array_filter($errors);

  return [$credentials, $errors];
}

// Ищет в БД пользователя с e-mail совпадающим с $email, в случае успеха помещает в глобальный массив $_SESSION и возвращает null. В случае неуспеха - возвращает сообщение.
function errorFindUserByEMail (mysqli $connection, string $email): ?string {
  $sql_take_user = "SELECT * FROM users WHERE users.email = ?;";
  $stmt_user = db_get_prepare_stmt($connection, $sql_take_user, [$email]);
  if(false === mysqli_stmt_execute($stmt_user)) {
    http_response_code(500);
    die("Ошибка взаимодействия с базой данных.");
  }

  $result_user = mysqli_stmt_get_result($stmt_user);
  $finded_user = mysqli_fetch_assoc($result_user);
  if (false === $finded_user || null === $finded_user) {
    return 'Пользователь с введённым e-mail не найден.';
  } else {
    $_SESSION['user']= $finded_user;
    return null;
  }
}

// Ищет пользователя с введёнными $credentials (логином-паролем) в БД. В случае успеха помещает его в массив $_SESSION, в случае неуспеха - выдаёт ошибки "не найден пользователь" или "пароль не совпадает с хранимым в БД"
function userLogin(mysqli $connection, array $credentials, array &$errors){
  $errors['email'] = errorFindUserByEMail($connection, $credentials['email']);
  if (empty($errors['email'])) {
    // Пользователь успешно найден, содержится в $_SESSION['user'], проверяем соответствие ввдённого пароля хэшу
    $errors['password'] = password_verify($credentials['password'], $_SESSION['user']['password']) ? null : 'Вы ввели неверный пароль';
    if (empty($errors['password'])) {
      header("Location: index.php");
      exit();
    } else {
      $_SESSION = [];
    }
  }
}
