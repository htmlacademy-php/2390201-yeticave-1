<?php
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

// Получение списка победителей по лотам в соответствии с условиями из ТЗ. Если победителей нет, вернёт null
function getWinners(mysqli $connection): ?array {
  $sql = "SELECT
    lots.id AS lot_id,
    lots.name AS lot_name,
    users.id AS lot_winner_id,
    users.name AS winner_name,
    users.email AS winner_email
  FROM lots
  JOIN bets ON bets.lot_id = lots.id
  JOIN users ON users.id = bets.user_id
  WHERE lots.expire_date <= CURRENT_TIMESTAMP
    AND lots.winner_id IS NULL
    AND bets.id = (
      SELECT bets.id
      FROM bets
      WHERE bets.lot_id = lots.id
      ORDER BY bets.make_time DESC, bets.id DESC
      LIMIT 1
    );";

  $stmt = db_get_prepare_stmt($connection, $sql);
  if(false === mysqli_stmt_execute($stmt)) {
    http_response_code(500);
    die("Ошибка взаимодействия с базой данных.");
  }

  $result = mysqli_stmt_get_result($stmt);
  return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Отправляет e-mail победителю с параметрами $winner через наш сервер рассылки
 * Возвращает true при успехе, false при ошибке.
 */
function sendEmailToWinner(array $winner): bool {
  try {
    // Конфигурация транспорта
    $dsn = MAIL_SERVER;
    $transport = Transport::fromDsn($dsn);

    // Формирование сообщения
    $message = new Email();
    $message->to($winner['winner_email']);
    $message->from(MAIL_SENDER);
    $message->subject(MAIL_SUBJECT . $winner['lot_name']);
    $msg_content = include_template('email.php', ['winner' => $winner]);
    $message->html($msg_content);

    // Отправка письма
    $mailer = new Mailer($transport);
    $mailer->send($message);
    return true;

  } catch (TransportExceptionInterface $e) {
    // Логируем ошибку
    error_log("Ошибка отправки email для {$winner['winner_email']}: " . $e->getMessage());
    return false;
  }
}

// Обновление сведений о победителе по лоту в БД
function updateWinner(mysqli $connection, array $winner) {
  // Обновляем в БД запись лота, добавляя туда id победителя
  $sql = "UPDATE lots SET winner_id = ? WHERE id = ?;";
  $stmt = db_get_prepare_stmt($connection, $sql, [$winner['lot_winner_id'], $winner['lot_id']]);
  $result = mysqli_stmt_execute($stmt);

  if ( false===$result || null===$result ) {
    http_response_code(500);
    die("Ошибка обновления сведений о победителе по лоту в базе данных");
  }
}
