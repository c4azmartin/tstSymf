SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `companies`
--

-- --------------------------------------------------------

--
-- Структура таблицы `organization`
--

CREATE TABLE IF NOT EXISTS `organization` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ogrn` bigint(20) DEFAULT NULL,
  `oktmo` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `tst_user`
--

CREATE TABLE IF NOT EXISTS `tst_user` (
  `id` int(11) NOT NULL,
  `organization` int(11) DEFAULT NULL,
  `secondname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `patronymic` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_birth` date DEFAULT NULL,
  `inn` bigint(20) unsigned DEFAULT NULL,
  `snils` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `organization`
--
ALTER TABLE `organization`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_C1EE637C6FC1CE2C` (`oktmo`),
  ADD UNIQUE KEY `org_id_uindex` (`id`),
  ADD UNIQUE KEY `org_oktmo_uindex` (`oktmo`),
  ADD UNIQUE KEY `org_ogrn_uindex` (`ogrn`);

--
-- Индексы таблицы `tst_user`
--
ALTER TABLE `tst_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_id_uindex` (`id`),
  ADD UNIQUE KEY `users_snils_uindex` (`snils`),
  ADD UNIQUE KEY `users_inn_uindex` (`inn`),
  ADD KEY `users_org_fk` (`organization`);

--
-- Ограничения внешнего ключа таблицы `tst_user`
--
ALTER TABLE `tst_user`
  ADD CONSTRAINT `FK_169F942DC1EE637C` FOREIGN KEY (`organization`) REFERENCES `organization` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
