-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3307
-- Время создания: Ноя 19 2026 г., 00:24
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `consultations_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `appointments`
--

CREATE TABLE `appointments` (
  `id` int NOT NULL,
  `slot_id` int NOT NULL,
  `student_id` int NOT NULL,
  `topic` text COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('active','completed') COLLATE utf8mb4_general_ci DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `appointments`
--

INSERT INTO `appointments` (`id`, `slot_id`, `student_id`, `topic`, `status`) VALUES
(1, 1, 3, 'Вопрос 1 по верстке', 'active'),
(2, 2, 3, 'Вопрос 2 по стилям', 'active'),
(3, 3, 4, 'Вопрос 3 по скриптам', 'active'),
(4, 4, 5, 'Вопрос 4 по формам', 'active'),
(5, 5, 3, 'Вопрос 5 по БД', 'active'),
(6, 6, 4, 'Вопрос 6 по SQL', 'active'),
(7, 7, 5, 'Вопрос 7 по связям', 'active'),
(8, 8, 3, 'Вопрос 8 по ключам', 'active'),
(9, 9, 4, 'Вопрос 9 по индексам', 'active'),
(10, 10, 5, 'Вопрос 10 по триггерам', 'active'),
(11, 11, 3, 'Вопрос 11 по сортировке', 'active'),
(12, 12, 4, 'Вопрос 12 по поиску', 'active'),
(13, 13, 5, 'Вопрос 13 по рекурсии', 'active'),
(14, 14, 3, 'Вопрос 14 по графам', 'active'),
(15, 15, 4, 'Вопрос 15 по деревьям', 'active'),
(16, 16, 5, 'Вопрос 16 по PHP', 'active'),
(17, 17, 3, 'Вопрос 17 по сессиям', 'active'),
(18, 18, 4, 'Вопрос 18 по массивам', 'active'),
(19, 19, 5, 'Вопрос 19 по циклам', 'active'),
(20, 20, 3, 'Вопрос 20 по функциям', 'active');

-- --------------------------------------------------------

--
-- Структура таблицы `slots`
--

CREATE TABLE `slots` (
  `id` int NOT NULL,
  `teacher_id` int NOT NULL,
  `subject` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `start_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `slots`
--

INSERT INTO `slots` (`id`, `teacher_id`, `subject`, `start_time`) VALUES
(1, 1, 'Веб-разработка', '2026-12-01 10:00:00'),
(2, 1, 'Веб-разработка', '2026-12-01 11:00:00'),
(3, 2, 'Базы данных', '2026-12-02 14:00:00'),
(4, 2, 'Базы данных', '2026-12-02 15:00:00'),
(5, 1, 'Веб-разработка', '2026-12-05 10:00:00'),
(6, 1, 'Веб-разработка', '2026-12-05 11:00:00'),
(7, 2, 'Базы данных', '2026-12-06 14:00:00'),
(8, 2, 'Базы данных', '2026-12-06 15:00:00'),
(9, 3, 'Алгоритмы', '2026-12-07 09:00:00'),
(10, 3, 'Алгоритмы', '2026-12-07 10:00:00'),
(11, 1, 'Веб-разработка', '2026-12-01 10:00:00'),
(12, 1, 'Веб-разработка', '2026-12-01 11:00:00'),
(13, 1, 'Веб-разработка', '2026-12-01 12:00:00'),
(14, 1, 'Веб-разработка', '2026-12-01 13:00:00'),
(15, 1, 'Веб-разработка', '2026-12-01 14:00:00'),
(16, 2, 'Базы данных', '2026-12-02 09:00:00'),
(17, 2, 'Базы данных', '2026-12-02 10:00:00'),
(18, 2, 'Базы данных', '2026-12-02 11:00:00'),
(19, 2, 'Базы данных', '2026-12-02 12:00:00'),
(20, 2, 'Базы данных', '2026-12-02 13:00:00'),
(21, 3, 'Алгоритмы', '2026-12-03 09:00:00'),
(22, 3, 'Алгоритмы', '2026-12-03 10:00:00'),
(23, 3, 'Алгоритмы', '2026-12-03 11:00:00'),
(24, 3, 'Алгоритмы', '2026-12-03 12:00:00'),
(25, 3, 'Алгоритмы', '2026-12-03 13:00:00'),
(26, 1, 'Веб-разработка', '2026-12-04 10:00:00'),
(27, 2, 'Базы данных', '2026-12-04 11:00:00'),
(28, 3, 'Алгоритмы', '2026-12-04 12:00:00'),
(29, 1, 'Веб-разработка', '2026-12-05 14:00:00'),
(30, 2, 'Базы данных', '2026-12-05 15:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `full_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `login` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('student','teacher') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `full_name`, `login`, `password`, `role`) VALUES
(1, 'Иванов Иван Иванович', 'teacher1', '12345', 'teacher'),
(2, 'Петров Петр Петрович', 'teacher2', '12345', 'teacher'),
(3, 'Сидорова Анна Сергеевна', 'student1', '12345', 'student'),
(4, 'Смирнов Алексей Дмитриевич', 'student2', '12345', 'student'),
(5, 'Сидоров С.С.', 'teacher3', '12345', 'teacher'),
(6, 'Кузнецова А.А.', 'student3', '12345', 'student'),
(7, 'Волков В.В.', 'student4', '12345', 'student');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slot_id` (`slot_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Индексы таблицы `slots`
--
ALTER TABLE `slots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT для таблицы `slots`
--
ALTER TABLE `slots`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`slot_id`) REFERENCES `slots` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `slots`
--
ALTER TABLE `slots`
  ADD CONSTRAINT `slots_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
