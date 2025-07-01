<?php
declare(strict_types=1);

// Получение содержимого лота по его id
function getLotById(mysqli $connection, int $lot_id): ?array
{
  //Получаем лот из БД по его id
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
      categories.name AS category
  FROM lots
  JOIN categories ON lots.category_id = categories.id
  WHERE lots.id = ?;";

  $stmt_lot = db_get_prepare_stmt($connection, $sql_take_lot, [$lot_id]);
  if(false === mysqli_stmt_execute($stmt_lot)) {
    http_response_code(500);
    die("Ошибка взаимодействия с базой данных.");
  }

  $result_lot = mysqli_stmt_get_result($stmt_lot);
  $lot = mysqli_fetch_assoc($result_lot);
  if (false === $lot || null === $lot) {
    http_response_code(404);
    die("Ошибка чтения лота с id=" . strval($lot_id));
  };

  // Находим максимальную ставку для лота с id = $lot_id, помещаем в $lot_price_array['price']
  $sql_take_lot_price = "SELECT MAX(bets.price) AS price FROM bets WHERE bets.lot_id = ?;";
  $stmt_lot_price = db_get_prepare_stmt($connection, $sql_take_lot_price, [$lot_id]);
  if(false === mysqli_stmt_execute($stmt_lot_price)) {
    http_response_code(500);
    die("Ошибка взаимодействия с базой данных.");
  }

  $result_lot_price = mysqli_stmt_get_result($stmt_lot_price);
  $lot_price_array = mysqli_fetch_assoc($result_lot_price);
  if (false === $lot_price_array || null === $lot_price_array) {
    http_response_code(404);
    die("Ошибка чтения максимальной ставки лота с id=" . strval($lot_id));
  };

  // Текущая цена - либо максимальная ставка, полученная предыдущим запросом (если она не NULL), либо стартовая цена лота
  $lot_price = $lot_price_array['price'] ?? $lot['start_price'];
  $min_bet = $lot_price + $lot['bet_step']; // Минимальная ставка по лоту

  return [
    'id' => $lot['id'],
    'name' => $lot['name'],
    'description' => $lot['description'],
    'image' => $lot['image'],
    'start_price' => $lot['start_price'],
    'expire_date' => $lot['expire_date'],
    'bet_step' => $lot['bet_step'],
    'author_id' => $lot['author_id'],
    'winner_id' => $lot['winner_id'],
    'category_id' => $lot['category_id'],
    'category' => $lot['category'],
    'price' => $lot_price,
    'min_bet' => $min_bet
  ];
}
