# [Giger]() #

**Cтартовый пакет для сайта НКО**

//лого или скрин

## Основные функции:

- адаптивный дизайн на базе Google Material Design
- предустановленный набор плагинов на базе WordPress
- автоматизация обновлений с помощью Composer
- SASS для создания стилей, минификация и автоматизация фронтенда с помощью Gulp
- инлайновые SVG для иконок и других изображений
- подерржка адаптивных изображений
- встроенные кнопки шаринга - Viber, Telegram, WhatsApp - для мобильных
- продуманная стартовая структура данных - новости, проекты, профили людей
- календарь событий
- формы подписки и контактные формы (с вомзожностью экспорта данных)
- пожертвования с помощью плагина Онлайн-Лейка
- несколько цветовых схем оформления

## Состав пакета

- ядро WordPress последней версии

- набор плагинов для реализации основных функций сайта

	- [Cyr to Lat enhanced](https://wordpress.org/plugins/cyr3lat/) 
	- [Crop-Thumbnails](https://wordpress.org/plugins/crop-thumbnails/) 
	- [Disable Comments](https://wordpress.org/plugins/disable-comments/)         
	- [Imsanity](https://wordpress.org/plugins/imsanity/) 
	- [Leyka](https://wordpress.org/plugins/leyka/) 
	- [Media Search Enhanced](https://wordpress.org/plugins/media-search-enhanced/) 
	- [Responsive Lightbox by dFactory](https://wordpress.org/plugins/responsive-lightbox/)         
	- [Simple CSS for widgets](https://wordpress.org/plugins/simple-css-for-widgets/) 
	- [Simple Google Maps Short Code](https://wordpress.org/plugins/simple-google-maps-short-code/) 
	- [Widget Logic](https://wordpress.org/plugins/widget-logic/) 
	- [Yoast SEO](https://wordpress.org/plugins/wordpress-seo/) 
	- [W3 Total Cache](https://wordpress.org/plugins/w3-total-cache/)
	- [Posts 2 Posts](https://wordpress.org/plugins/posts-to-posts/) 
	- [Ninja Forms](https://wordpress.org/plugins/ninja-forms/) 
	- [CMB2](https://wordpress.org/plugins/cmb2/)
	
- набор служебных плагинов "для разработчиков"

	- [Debug Bar](https://wordpress.org/plugins/debug-bar/) 
	- [Query Monitor](https://wordpress.org/plugins/query-monitor/)       
	- [Debug Objects](https://wordpress.org/plugins/debug-objects/) 
	- [WP Sync DB](https://github.com/wp-sync-db/wp-sync-db) 
	- [WP Sync DB Media Files](https://github.com/wp-sync-db/wp-sync-db-media-files)
	- [Force Regenerate Thumbnails](https://wordpress.org/plugins/force-regenerate-thumbnails/) 
	- [Post Duplicator](https://wordpress.org/plugins/post-duplicator/) 
	
- тема оформления Giger

- набор демо-данных в виде дампа базы

- набор демо-изображений 


## Системные требования 

Установка осуществляется на LAMP комплекс, удовлетворяющий следующим требованиям:

- версия PHP не ниже 5.6
- версия MySQL не ниже 5.6 (необходима поддержка utf8mb4 кодировки)

Для реализации установки необходимы:

- установленный менеджер зависимостей [Composer](https://getcomposer.org/) для PHP ([подробнее об установке](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)), который может запускаться в папке проекта
- созданная для сайта база данных и параметры доступа к ней (хост, имя базы, имя пользователя и пароль)
- в случае локальной установки: конфигурация на поддержку вирутального хоста giger.local
- в случае установки на удаленный сервер: домен, указывающий на папку проекта

## Процесс установки

1. Клонировать или скопировать в папку проекта исходный код из репозитория.

2. Скопировать и переименовать файл `wp-config-orig.php` в `wp-config.php`. В полученном файле заполнить информацию о доступе к БД, ключи аутентификации и домен, если устанавливаем не на giger.local.

3. Запустить команду `composer install` в папке проекта - в результате будет установлен WordPress последней версии и необходимые плагины.

4. Импортировать в БД дамп с демо-данными - `attachments/startertest.sql.zip`. Если установка осуществляется для домена отличного от `giger.local` выполнить замену домена в базе с использованием соответствующих утилит, поддерживающих сериализованные данные - рекомендуем [Search and Replace скрипт от interconnect/it](https://interconnectit.com/products/search-and-replace-for-wordpress-databases/).

5. Распаковать содержимое папки `attachments/uploads.zip` в `wp-content/uploads`.

6. Зайти на сайт по адресу _giger.local_ или вашему домену. Вход в административную часть по адресу _giger.local/core/wp-login.php_ с логином _giger_ и паролем _121121_. Необходимо создать нового пользователя, используя стандартный диалог WordPress: _Меню - Пользователи - Добавить нового_, а аккаунт _giger_ удалить.

7. Сгенерировать заново правила ЧПУ: необходимо зайти на страницу в административном интерфейсе _Меню - Настйроки - Постоянные ссылки_ и сохранить настройки без изменений.

На данном этапе сайт работает и можно вносить изменение в его содержание. Однако, если вы хотите также корректировать код темы, для этого потребуются некоторые дополнительные настройки рабочего окружения для использования таск-менеджера [gulp](http://gulpjs.com/).

//про гульп допишем тут


## Помощь проекту

Giger создан и поддерживается [Теплицей социальных технологий](https://te-st.ru).

Вы можете помочь следующими способами:

  * Добавить сообщение об ошибке или предложение по улучшению на GitHub.
  * Поделиться улучшениями кода, послав нам Pull Request.
  * Сделать перевод системы или оптимизировать его для вашей страны (перевод на англ. уже существует).
  
Если вам нужна помощь волонтеров в установке и настройке - создайте задачу на [https://itv.te-st.ru](https://itv.te-st.ru).

