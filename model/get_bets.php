<?php

// Получение всех ставок для пользователя с id равным $user_id. В случае отсутствия ставок по пользователю вернёт null
function getLotBets(mysqli $connection, int $user_id): ?array{
  $sql = "SELECT
    lots.image AS image,
    categories.name AS category,
    lots.name AS lot_name,
    bets.lot_id AS lot_id,
    bets.price AS price,
    bets.make_time AS make_time,
    lots.expire_date AS expire_date,
    (lots.expire_date < CURRENT_TIMESTAMP) AS expired,
    (bets.price = max_bets.max_price AND lots.expire_date < CURRENT_TIMESTAMP) AS win,
    users.contacts AS author_contacts
  FROM bets
  JOIN lots ON lots.id = bets.lot_id
  JOIN categories ON categories.id = lots.category_id
  JOIN users ON users.id = lots.author_id
  JOIN (
    SELECT lot_id, MAX(price) AS max_price
    FROM bets
    GROUP BY lot_id
  ) AS max_bets ON max_bets.lot_id = bets.lot_id
  WHERE bets.user_id = ?
  ORDER BY make_time DESC;";

  $stmt = db_get_prepare_stmt($connection, $sql, [$user_id]);
  if(false === mysqli_stmt_execute($stmt)) {
    http_response_code(500);
    die("Ошибка взаимодействия с базой данных.");
  }

  $result = mysqli_stmt_get_result($stmt);
  return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
