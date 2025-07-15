<?php
// Сценарий выхода пользователя с сайта
session_start();

$_SESSION = [];
header("Location: ./index.php");
