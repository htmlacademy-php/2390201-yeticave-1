<?php
// Обработка перечня лотов по выбранной категории

function categoryNameByID(array $categories, int $category_id): string {
  for ( $i = 0; $i < count($categories); $i++) {
    if ($categories[$i]['id'] == $category_id) {
      return $categories[$i]['name'];
    }
  }
  return 'такой категории нет';
}

// Получение количества страниц, соответствующих списку лотов по запросу $search_content
function categoryPagesNumber(mysqli $connection, int $category_id): int {
  $sql_count_lots = "SELECT COUNT(*) AS total FROM lots WHERE category_id = ?";

  $stmt = db_get_prepare_stmt($connection, $sql_count_lots, [$category_id]);
  if (false === mysqli_stmt_execute($stmt)) {
    http_response_code(500);
    die("Ошибка взаимодействия с базой данных.");
  }

  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);
  return (bool)$row ? ceil((int)$row['total'] / LOTS_ON_PAGE) : 0;
}

// Получение списка лотов по поисковому запросу $search_content
function categoryLotsFinded(mysqli $connection, int $category_id, int $offset): ?array {
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
  WHERE category_id = ?
  GROUP BY lots.id
  ORDER BY lots.publish_date DESC, lots.id ASC
  LIMIT ? OFFSET ?;";

  $stmt_lots = db_get_prepare_stmt($connection, $sql_take_lots, [$category_id, LOTS_ON_PAGE, $offset]);
  if(false === mysqli_stmt_execute($stmt_lots)) {
    http_response_code(500);
    die("Ошибка взаимодействия с базой данных.");
  }

  $result_lots = mysqli_stmt_get_result($stmt_lots);
  return mysqli_fetch_all($result_lots, MYSQLI_ASSOC);
}
