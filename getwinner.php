<?php
// Сценарий определения списка победителей по лотам, срок которых истёк, и рассылки писем с сообщениями о победе

// Подключение функций, инициализация переменных для работы сайта
require_once 'init.php';

// Подключаем функции getWinners, sendEmailToWinner, updateWinner
require_once 'model/treat_winners.php';

$winners = getWinners($connection);
if ($winners) {
  foreach ($winners as $winner) {
    if (!sendEmailToWinner($winner)) {
      //Уведомляем администратора сайта об ошибке отправки рассылки, например
      echo "Не удалось отправить письмо для: " . $winner['winner_email'] . "<br>";
    }
    updateWinner($connection, $winner);
  }
}
