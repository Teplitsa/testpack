# Сайт отделения Красного Креста
Победитель конкураса малых сайтов

**Установка на хостинг**

Нужно:
- LAMP: PHP 5.6+ и MySQL 5.6+ (поддержка кодировки utf8mb4)
- Composer для PHP (подробнее об установке), с правами на запуск в папке проекта
- На удаленном сервере: домен, указывающий на папку проекта

1. Зайти в папку, в которой должен размещаться код сайта (DocumentRoot) и опустошить ее. В ней не должно быть файлов, иначе клонирование будет невозможно.

2. Клонировать репозиторий:
	- `git clone -b kms-n-redcross-master https://github.com/Teplitsa/testpack .` (не забудьте точку в конце, она заставляет клонировать код в ту папку, в которой вы находитесь).
	- Если не хостинге не установлен git, можно скопировать архив с сайта https://github.com/Teplitsa/testpack/tree/kms-n-redcross-master, распаковать его и разместить содержимое папки testpack-kms-n-redcross-master на хостинге.
	
3. Создать базу и импортировать в нее тестовые данные:
	- `echo 'CREATE DATABASE IF NOT EXISTS YOUR_DB' | mysql --user=your_db_username --password=your_db_password` Если в MySQL нет юзера, который имеет права для создания БД, то можно создать БД через административную панель хостинга.
	- `unzip -p ./attachments/startertest.sql.zip | mysql --user=your_db_username --password=your_db_password YOUR_DB`
	- Если выполнить команду загрузки данных не получилось, то можно загрузить данные через панель управления хостингом, используя PHPMyAdmin. Для этого нужно импортировать данные из файла ./attachments/startertest.sql.zip.
	
4. Запустить: composer install если не срабатывает, то:
	- скачайте composer прямо в папку сайта:
	- `php -r "readfile('https://getcomposer.org/installer');" | php`
	- запустите отключив ограничение памяти:
	- `php -d memory_limit=-1 composer.phar install`
	- если на сервере несколько версий php, то вместо php в этих командах нужно указывать конкретную версию:
	- `php5.5 -r "readfile('https://getcomposer.org/installer');" | php5.5`
	
5. Создать конфигурационный файл из шаблона и заполнить в нем информацию о доступе к базе данных и домен:
	- `cat wp-config-orig.php | sed 's/dev_db/giger/g;s/dev_user/your_db_username/g;s/dev_password/your_db_password/g;s/giger\.local/yourredcross\.ru/g' > wp-config.php`
	
6. Распаковать содержимое папки с изображениями attachments/uploads.zip в wp-content/uploads:
	- `unzip ./attachments/uploads.zip -d ./wp-content/`
	
7. Создать файл .htaccess из шаблона и настроить права доступа к нему:
	- cat ./attachments/.htaccess.orig > .htaccess
	- chmod -v 666 .htaccess
	
8. В базе WP заменить домен giger.local на yourredcross.ru. Для этого нужно скачать утилиту Search-Replace-DB со страницы https://interconnectit.com/products/search-and-replace-for-wordpress-databases/ в папку сайта. Зайти в нее и запустить 2 команды:
	- `php srdb.cli.php -h localhost -n YOUR_DB -u YOUR_DB_USER -p YOUR_DB_PASSWORD -s http://giger.local -r http://yourredcross.ru`
	- `php srdb.cli.php -h localhost -n YOUR_DB -u YOUR_DB_USER -p YOUR_DB_PASSWORD -s giger.local -r yourredcross.ru`
	- Удалить утилиту с сервера.
	
9. Сайт отвечает по адресу http://yourredcross.ru. Вход в админку http://yourredcross.ru/core/wp-login.php с логином giger и паролем 121121. Необходимо создать нового пользователя http://yourredcross.ru/core/wp-admin/user-new.php, а аккаунт giger удалить.

