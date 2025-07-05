<?php

// Функция заполняет поля добавляемого лота из массивов $_POST и $_FILES, одновременно валидируя поля на ошибки
function getLotAndErrors(array $categories): array {
  $lot = [];
  $errors = [];

  // Получение полей лота из массива $_POST с проверкой на ошибки
  $lot['name'] = htmlspecialchars($_POST['lot-name']);
  $errors['name'] = validateLength($lot['name'], 1, 128); // Тут и ниже убрать цифры и сообщения в константы

  $lot['description'] = htmlspecialchars($_POST['message']);
  $errors['description'] = validateLength($lot['description'], 1, 255);

  $lot['start_price'] = filter_var($_POST['lot-rate'], FILTER_VALIDATE_INT);
  $errors['start_price'] = !$lot['start_price'] ? "Введите сумму в рублях" : null;

  $lot['expire_date'] = $_POST['lot-date'];
  $errors['expire_date'] = validateDate($lot['expire_date']);

  $lot['bet_step'] = filter_var($_POST['lot-step'], FILTER_VALIDATE_INT);
  $errors['bet_step'] = !$lot['bet_step'] ? "Введите сумму в рублях" : null;

  $lot['author_id'] = 1;

  $lot['winner_id'] = null;

  $lot['category_id'] = $_POST['category'];
  $errors['category'] = validateCategory($lot['category_id'], $categories);

  // Получение пути к файлу-картинке и его загрузка в директорию для картинок
  $lot['image'] = '';
  if (empty($_FILES['lot-img']['name'])) {
    $errors['image'] = 'Загрузите изображение лота.';
  } else {
    $image_extention = defineImgFileExtention($_FILES['lot-img']['type']);
    if ($image_extention === '') {
      $errors['image'] = 'Для загруки изображения лота выберите графический файл.';
    } else {
      $lot['image'] = './uploads/' . uniqid() . $image_extention;
      move_uploaded_file($_FILES['lot-img']['tmp_name'], $lot['image']);
    };
  }

  // Оставляем в массиве ошибок только не null значения
  $errors = array_filter($errors);

  return [$lot, $errors];
}

// Функция добавляет лот в БД при успешной отправке формы.
function addLot(mysqli $connection, array $lot) {
   // Добавляем в БД запись о новом лоте
  $sql_add_lot = "INSERT INTO lots
  (name, description, image, start_price, expire_date, bet_step, author_id, winner_id, category_id)
  VALUES
  (?, ?, ?, ?, ?, ?, ?, ?, ?);";
  $stmt_add_lot = db_get_prepare_stmt($connection, $sql_add_lot, [$lot['name'], $lot['description'], $lot['image'], $lot['start_price'], $lot['expire_date'], $lot['bet_step'], $lot['author_id'], $lot['winner_id'], $lot['category_id']]);
  $result_add_lot = mysqli_stmt_execute($stmt_add_lot);

  if ( false===$result_add_lot || null===$result_add_lot ) {
    http_response_code(500);
    die("Ошибка добавления лота в базу данных");
  } else {
    $lot_id = mysqli_insert_id($connection);
    header("Location: lot.php?id=".strval($lot_id));
  };
}
