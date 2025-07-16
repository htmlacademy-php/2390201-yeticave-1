<?php
// Обработка поисковых запросов
// Получение количества страниц, соответствующих списку лотов по запросу $search_content
function pagesNumber(mysqli $connection, string $search_content): int {
  $sql_count_lots = "SELECT COUNT(*) AS total FROM lots WHERE MATCH(lots.name, lots.description) AGAINST(?);";

  $stmt = db_get_prepare_stmt($connection, $sql_count_lots, [$search_content]);
  if (false === mysqli_stmt_execute($stmt)) {
    http_response_code(500);
    die("Ошибка взаимодействия с базой данных.");
  }

  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);
  if ($row) {
    return ceil((int)$row['total'] / LOTS_ON_PAGE);
  } else {
    return 0;
  }
}

// Получение списка лотов по поисковому запросу $search_content
function lotsFinded(mysqli $connection, string $search_content, int $offset): ?array {
  $sql_take_lots = "SELECT
    lots.id,
    lots.name,
    lots.description,
    lots.image,
    lots.start_price,
    lots.expire_date,
    COUNT(bets.id) AS bets_number,
    MAX(bets.price) AS current_price,
    categories.name AS category
  FROM lots
  JOIN categories ON lots.category_id = categories.id
  LEFT JOIN bets ON lots.id = bets.lot_id
  WHERE MATCH(lots.name, lots.description) AGAINST(?)
  GROUP BY lots.id
  ORDER BY lots.id LIMIT ? OFFSET ?;";

  $stmt_lots = db_get_prepare_stmt($connection, $sql_take_lots, [$search_content, LOTS_ON_PAGE, $offset]);
  if(false === mysqli_stmt_execute($stmt_lots)) {
    http_response_code(500);
    die("Ошибка взаимодействия с базой данных.");
  }

  $result_lots = mysqli_stmt_get_result($stmt_lots);
  return mysqli_fetch_all($result_lots, MYSQLI_ASSOC);
}
