USE yeticave;

-- Заполнение таблицы категорий лотов начальными данными
INSERT INTO categories
  (name, code)
VALUES
  ('Доски и лыжи', 'boards'),
  ('Крепления', 'attachment'),
  ('Ботинки', 'boots'),
  ('Одежда', 'clothing'),
  ('Инструменты', 'tools'),
  ('Разное', 'other');

-- Заполнение таблицы пользователей начальными данными
INSERT INTO users
  (reg_date, email, name, password, contacts)
VALUES
  (CURRENT_TIMESTAMP, 'romnosk@gmail.com', 'Роман Носков', '', 'Адрес и телефон Р.Носкова'),
  (CURRENT_TIMESTAMP, 'iivanov@example.com', 'Иван Иванов', '', 'Адрес и телефон И.Иванова'),
  (CURRENT_TIMESTAMP, 'ppetrov@example.com', 'Пётр Петров', '', 'Адрес и телефон П.Петрова');

-- Заполнение таблицы лотов начальными данными
INSERT INTO lots
  (name, description, image, start_price, expire_date, bet_step, author_id, winner_id, category_id)
VALUES
  ('014 Rossignol District Snowboard', '014 Rossignol District Snowboard', './img/lot-1.jpg', 10999, '2025-07-28', 100, 1, NULL, 1),
  ('DC Ply Mens 2016/2017 Snowboard', 'DC Ply Mens 2016/2017 Snowboard', './img/lot-2.jpg', 159999, '2025-07-29', 100, 1, NULL, 1),
  ('Крепления Union Contact Pro 2015 года размер L/XL', 'Крепления Union Contact Pro 2015 года размер L/XL', './img/lot-3.jpg', 8000, '2025-07-30', 100, 2, NULL, 2),
  ('Ботинки для сноуборда DC Mutiny Charocal', 'Ботинки для сноуборда DC Mutiny Charocal', './img/lot-4.jpg', 10999, '2025-07-28', 100, 2, NULL, 3),
  ('Куртка для сноуборда DC Mutiny Charocal', 'Куртка для сноуборда DC Mutiny Charocal', './img/lot-5.jpg', 7500, '2025-06-12', 100, 3, NULL, 4),
  ('Маска Oakley Canopy', 'Маска Oakley Canopy', './img/lot-6.jpg', 5400, '2025-06-08', 100, 3, NULL, 6);

-- Заполнение таблицы ставок начальными данными
INSERT INTO bets
  (make_time, price, user_id, lot_id)
VALUES
  (CURRENT_TIMESTAMP, 11500, 2, 1),
  (CURRENT_TIMESTAMP, 12000, 3, 1),
  (CURRENT_TIMESTAMP, 8100, 2, 3),
  (CURRENT_TIMESTAMP, 8200, 3, 3),
  (CURRENT_TIMESTAMP, 8300, 2, 3);

-- Запрос: получить все категории
SELECT * FROM categories;

-- Запрос: получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории
SELECT
  lots.name,
  lots.start_price,
  lots.image,
  MAX(bets.price) AS lot_price,
  categories.name AS category_name
FROM lots
JOIN categories ON lots.category_id = categories.id
LEFT JOIN bets ON lots.id = bets.lot_id
WHERE lots.expire_date >= CURRENT_TIMESTAMP
GROUP BY lots.id;

-- Запрос: показать лот по его ID=1. Получите также название категории, к которой принадлежит лот
SELECT
  lots.name,
  lots.start_price,
  lots.image,
  categories.name AS category_name
FROM lots
JOIN categories ON lots.category_id = categories.id
WHERE lots.id = 1;

-- Запрос: получить список ставок для лота с идентификатором = 1 с сортировкой по дате
SELECT * FROM bets WHERE lot_id = 1 ORDER BY make_time ASC;

-- Обновить название лота 1 по его идентификатору
UPDATE lots SET name = '014-2 Rossignol Snowboard' WHERE id = 1;
