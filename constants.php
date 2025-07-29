<?php

const TIMER_FINISING_HOURS = 1; // Количество часов для сигнала красным в таймере что срок лота истекает
const LOTS_ON_PAGE = 9; // Количество лотов, выводимое на одну страницу при поиске
const SITE_ADDRESS = 'http://localhost/part1'; //Адрес нашего сайта в интернете (без слэша в конце)

//Параметры отправки почтовых рассылок
const MAIL_SERVER = 'smtp://mailuser:1234@localhost:465?encryption=tls&auth_mode=login';
const MAIL_SENDER = 'keks@phpdemo.ru';
const MAIL_SUBJECT = 'Ваша ставка победила в лоте ';
