<?php
// Проверяет введённый e-mail нового пользователя на уникальность
function validateUniqEMail(mysqli $connection, string $email) {
  $sql_take_email = "SELECT email FROM users WHERE users.email = ?;";
  $stmt_email = db_get_prepare_stmt($connection, $sql_take_email, [$email]);
  if(false === mysqli_stmt_execute($stmt_email)) {
    http_response_code(500);
    die("Ошибка взаимодействия с базой данных.");
  }

  $result_email = mysqli_stmt_get_result($stmt_email);
  $finded_email = mysqli_fetch_assoc($result_email);
  if (false === $finded_email || null === $finded_email) {
    return null;
  } else {
    return "Введённый e-mail уже зарегистрирован";
  }
}

// Заполняет поля нового пользователя из массива $_POST, одновременно валидируя поля на ошибки
function getUserAndErrors(mysqli $connection): array {
  $user = [];
  $errors = [];

  // Получение полей нового пользователя из массива $_POST с проверкой на ошибки
  $user['reg_date'] = date("Y-m-d H:i:s");

  $user['email'] = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
  if (!$user['email']) {
    $errors['email'] = "Введите e-mail в формате user@example.com";
  } else {
    $errors['email'] = validateLength($user['email'], 1, 128);
    if ($errors['email'] === null) {
      $errors['email'] = validateUniqEMail($connection, $user['email']);
    }
  }

  $user['name'] = htmlspecialchars($_POST['name']);
  $errors['name'] = validateLength($user['name'], 1, 255);

  $user['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $errors['password'] = validateLength($user['password'], 1, 128);

  $user['contacts'] = htmlspecialchars($_POST['message']);
  $errors['contacts'] = validateLength($user['contacts'], 1, 1024);

  // Оставляем в массиве ошибок только не null значения
  $errors = array_filter($errors);

  return [$user, $errors];
}

// Добавляет нового пользователя в БД при успешной отправке формы.
function addUser(mysqli $connection, array $user) {
  $sql_add_user = "INSERT INTO users
  (reg_date, email, name, password, contacts)
  VALUES
  (?, ?, ?, ?, ?);";
  $stmt_add_user = db_get_prepare_stmt($connection, $sql_add_user, [$user['reg_date'], $user['email'], $user['name'], $user['password'], $user['contacts']]);
  $result_add_user = mysqli_stmt_execute($stmt_add_user);

  if ( false===$result_add_user || null===$result_add_user ) {
    http_response_code(500);
    die("Ошибка добавления нового пользователя в базу данных");
  } else {
    header("Location: ./login.php");
  };
}
