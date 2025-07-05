
<?php
// Устанавливаем текущую временную зону "Москва GMT+3" для правильной работы с датами
date_default_timezone_set('Europe/Moscow');

// Функция get_dt_range принимает в качестве параметра дату в формате ГГГГ-ММ-ДД,
// и возвращает массив строк, где первый элемент — целое количество часов до даты,
// а второй — остаток в минутах.
function get_dt_range(string $expire_date) {
  $time_left = ['00', '00'];

  $current_time = time();
  $expire_time = strtotime($expire_date);

  $minutes_difference = floor(($expire_time - $current_time) / 60);
  if ($minutes_difference > 0) {
    $time_left[0] = str_pad(strval(floor($minutes_difference / 60)), 2, '0', STR_PAD_LEFT);
    $time_left[1] = str_pad(strval($minutes_difference % 60), 2, '0', STR_PAD_LEFT);
  }
  return $time_left;
}

// Функция для сохранения ранее введённого значения в поле формы с аттрибутом name = $name
function getPostVal($name) {
  return $_POST[$name] ?? "";
}

// Функция определяет расширение графического файла по его mime - типу. Если не находит подходящего типа, возвращает пустую строку.
function defineImgFileExtention(string $mime_type) {
  $image_file_types = [
    ['.png', 'image/png'],
    ['.jpe', 'image/jpeg'],
    ['.jpg', 'image/jpeg'],
    ['.gif', 'image/gif'],
    ['.bmp', 'image/bmp'],
    ['.ico', 'image/vnd.microsoft.icon'],
    ['.tiff', 'image/tiff'],
    ['.svg', 'image/svg+xml'],
  ];

  $file_extention = '';
  foreach ($image_file_types as $value) {
    if ($value[1]===$mime_type) {
      $file_extention = $value[0];
    }
  }
  return $file_extention;
}

// Проверяет длину $value, чтобы попадала между $min и $max
function validateLength ($value, $min, $max) {
  if($value) {
    $len = strlen($value);
    if ($len < $min or $len > $max) {
      return "Значение должно быть от $min до $max символов";
    }
  } else {
    return "Значение не должно быть пустым";
  }
  return null;
}

// Проверяет наличие id категории в массиве категорий
function validateCategory($id, $categories) {
  $findCategory = false;
  foreach($categories as $category) {
    if ($id === $category['id']) {
      $findCategory = true;
    }
  }
  return $findCategory ? null : "Вы не выбрали категорию";
}

// Проверяет дату на соответствие формату "ГГГГ-ММ-ДД"
function validateDate($date) {
  preg_match('/^(\\d{4})\\-(\\d{2})\\-(\\d{2})$/', $date, $m);
  if (!checkdate($m[2], $m[3], $m[1])) {
    return "Введите дату в формате 'ГГГГ-ММ-ДД'";
  };

  if ($date === date('Y-m-d')) {
    return "Дата завершения торгов по лоту не должна быть сегодняшней";
  }

  return null;
}
