<?php
// Функции для обработки данных по лоту и его ставкам на странице просмотра лота.

// Получение содержимого лота по его id
function getLotById(mysqli $connection, int $lot_id): ?array {
  //Получаем лот из БД по его id, вычисляем в запросе текущую цену и минимальную ставку
  $sql_take_lot = "SELECT
    lots.id,
    lots.name,
    lots.description,
    lots.image,
    lots.start_price,
    lots.expire_date,
    lots.bet_step,
    lots.author_id,
    lots.winner_id,
    lots.category_id,
    categories.name AS category,
    COALESCE(max_bets.max_price, lots.start_price) AS price,
    COALESCE(max_bets.max_price, lots.start_price) + lots.bet_step AS min_bet
  FROM lots
  JOIN categories ON lots.category_id = categories.id
  LEFT JOIN (
      SELECT lot_id, MAX(price) AS max_price
      FROM bets
      GROUP BY lot_id
    ) AS max_bets ON max_bets.lot_id = lots.id
  WHERE lots.id = ?;";

  $stmt_lot = db_get_prepare_stmt($connection, $sql_take_lot, [$lot_id]);
  if (false === mysqli_stmt_execute($stmt_lot)) {
    http_response_code(500);
    die("Ошибка взаимодействия с базой данных.");
  }

  $result_lot = mysqli_stmt_get_result($stmt_lot);
  $lot = mysqli_fetch_assoc($result_lot);
  if (false === $lot || null === $lot) {
    http_response_code(404);
    die("Ошибка чтения лота с id=" . strval($lot_id));
  };

  return $lot;
}

// Получение всех ставок по лоту с id равным $lot_id. В случае отсутствия ставок по лоту вернёт null
function getLotBets(mysqli $connection, int $lot_id): ?array{
  $sql = "SELECT
    bets.price,
    bets.make_time,
    bets.user_id,
    users.name AS user_name
  FROM bets
  JOIN users ON bets.user_id = users.id
  WHERE bets.lot_id = ?
  ORDER BY make_time DESC;";

  $stmt = db_get_prepare_stmt($connection, $sql, [$lot_id]);
  if(false === mysqli_stmt_execute($stmt)) {
    http_response_code(500);
    die("Ошибка взаимодействия с базой данных.");
  }

  $result = mysqli_stmt_get_result($stmt);
  return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Валидация формы добавления ставки и получение ставки из формы.
// Возвращает array[$bet[], $errors['bet_price']], где
// $bet[] - ставка, заполненная для добавления в БД
// $errors['bet_price'] - строка ошибки при вводе ставки
function getBetAndErrors(array $lot): array {
  if (!isset($_SESSION['user'])) {
    return[[],['bet_price' => 'Только авторизованный пользователь может добавить ставку']];
  }

  $bet['price'] = filter_var($_POST['cost'], FILTER_VALIDATE_INT);
  if (!$bet['price']) {
    return[[],['bet_price' => 'Введите сумму в рублях']];
  }

  if($bet['price'] < $lot['min_bet']) {
    return[[],['bet_price' => 'Ваша ставка не должна быть меньше минимальной ставки']];
  }

  //Нет ошибок при вводе ставки - заполняем её полностью
  $bet['make_time'] = date("Y-m-d H:i:s");
  $bet['user_id'] = $_SESSION['user']['id'];
  $bet['lot_id'] = $lot['id'];
  return [$bet, []];
}

// Добавление ставки в БД
function addBet(mysqli $connection, array $bet) {
  // Добавляем в БД запись о новой ставке
 $sql = "INSERT INTO bets (make_time, price, user_id, lot_id) VALUES (?, ?, ?, ?);";
 $stmt = db_get_prepare_stmt($connection, $sql, [$bet['make_time'], $bet['price'], $bet['user_id'], $bet['lot_id']]);
 $result = mysqli_stmt_execute($stmt);

 if ( false===$result || null===$result ) {
   http_response_code(500);
   die("Ошибка добавления ставки в базу данных");
 }
}

/**
* Проверяет, нужно ли показывать блок добавления ставки на странице просмотра лота.
* Блок добавления ставки не показывается, если:
* - пользователь не авторизован;
* - срок размещения лота истёк;
* - лот создан текущим пользователем;
* - последняя ставка сделана текущим пользователем.
*/
function addLotAllowed (array $lot, array $lot_bets): bool {
  if(!isset($_SESSION['user'])) {
    return false;
  }

  if (get_dt_range($lot['expire_date']) === ['00','00','00']) {
    return false;
  }

  if ($lot['author_id'] === $_SESSION['user']['id']) {
    return false;
  }

  if ($lot_bets && $lot_bets[0]['user_id'] === $_SESSION['user']['id']) {
    return false;
  }

  return true;
}

